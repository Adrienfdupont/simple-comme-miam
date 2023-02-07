<?php

abstract class Model
{
    public $id;

    public $created_at;

    public $updated_at;

    private static $pdo;

    public function __construct()
    {
        $this->created_at = date('Y-m-d H:m:s');
    }

    // affectation de la propriété $pdo si elle n'existe pas déjà sinon on la retourne
    private static function getPDO(): object
    {
        if (self::$pdo === null) {
            $db_name = 'mysql:host=mysql-adrienf.alwaysdata.net;dbname=adrienf_simple_comme_miam';
            $user = 'adrienf';
            $password = 'mmdppbdd3$PV?';
            $options = [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            try {
                self::$pdo = new PDO($db_name, $user, $password, $options);
            } catch (PDOException $pe) {
                echo "ERREUR : " . $pe->getMessage();
            }
        }
        return self::$pdo;
    }

    private static function getClassName(object $class): string
    {
        $classInfos = new ReflectionClass($class);
        return $classInfos->getShortName();
    }

    // convertir le nom de la classe en nom de table
    private static function getTableName(string $className): string
    {
        // si classe fille = User alors table = users mais si classe = Category alors table = categories
        if ($className[-1] == 'y') {
            $tableName = substr(strtolower($className), 0, -1) . 'ies';
        } else {
            $tableName = strtolower($className) . 's';
        }
        return $tableName;
    }

    // permet d'insérer une ligne ou de la modifier si un id existe déjà pour celle-ci
    public function save(): void
    {
        // obtenir infos de la classe instanciée
        $classInfos = new ReflectionClass($this);

        // on remplit un tableau qui associe les noms des colonnes avec les propriétés des instances
        $propertyArray = [];

        // les colonnes correspondent aux propriétés publiques
        foreach ($classInfos->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();

            // pour un champ nul
            if (is_null($this->{$propertyName})) {
                $propertyArray[] = '`' . $propertyName . '` = NULL';

                // pour un champ de type varchar
            } elseif (is_string($this->{$propertyName})) {
                $propertyArray[] = '`' . $propertyName . '` = "' . $this->{$propertyName} . '"';
            } else {
                $propertyArray[] = '`' . $propertyName . '` = ' . $this->{$propertyName};
            }
            // à chaque requête il y a modification de la ligne
            $this->updated_at = date('Y-m-d H:m:s');
        }
        // on convertit le tableau en string
        $setClause = implode(',', $propertyArray);

        // on intègre le string dans la requête
        if (isset($this->id)) {

            // pour une ligne qui existerait déjà
            $sqlQuery = 'UPDATE `' . self::getTableName(self::getClassName($this)) . '` SET ' . $setClause . ' WHERE id = ' . $this->id;
        } else {

            // pour une ligne qui n'existerait pas encore
            $sqlQuery = 'INSERT INTO `' . self::getTableName(self::getClassName($this)) . '` SET ' . $setClause;
        }
        $request = self::getPDO()->prepare($sqlQuery);
        $request->execute();
    }

    public static function where(string $condition): array  // pour trouver une ou plusieurs lignes d'après une condition passée en paramètre
    {
        $classInfos = new ReflectionClass(get_called_class());

        // la condition est intégrée à la requête
        $sqlQuery = 'SELECT * FROM ' . self::getTableName($classInfos->getShortName()) . ' WHERE ' . $condition;

        $request = self::getPDO()->prepare($sqlQuery);
        $request->execute();

        // on stocke la classe fille dans une variable
        $class = get_called_class();

        // on retourne un tableau d'objets au cas où la requête retournerait plusieurs lignes
        $lines = $request->fetchAll(PDO::FETCH_ASSOC);
        $objects = [];

        // on parcourt les lignes retournées par la requête
        foreach ($lines as $line) {

            // à chaque propriété de la classe fille, on assigne sa valeur à l'objet retourné
            $object = new $class();
            foreach ($classInfos->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
                $propertyName = $property->getName();
                $object->{$propertyName} = $line[$propertyName];
            }
            $objects[] = $object;
        }
        return $objects;
    }

    // supprimer une ligne en passant par l'objet qui la représente
    public function delete(): void
    {
        $sqlQuery = 'DELETE FROM ' . self::getTableName(self::getClassName($this)) . ' WHERE id = ' . $this->id;

        $request = self::getPDO()->prepare($sqlQuery);
        $request->execute();
    }

    // supprimer des lignes en passant par la classe directement
    public static function deleteWhere(string $condition): void
    {
        $sqlQuery = 'DELETE FROM ' . get_called_class() . ' WHERE ' . $condition;
        $request = self::getPDO()->prepare($sqlQuery);
        $request->execute();
    }

    public function join(string $className, string $col1, string $col2)
    {
        $sqlQuery = 'SELECT ' . self::getTableName($className) . '.*' .
            ' FROM ' . self::getTableName(self::getClassName($this)) .
            ' INNER JOIN ' . self::getTableName($className) .
            ' ON ' . $col1 . ' = ' . $col2 .
            ' WHERE ' . self::getTableName(self::getClassName($this)) . '.id = ' . $this->id;

        $request = Model::getPDO()->prepare($sqlQuery);
        $request->execute();
        $lines = $request->fetchAll(PDO::FETCH_ASSOC);
        $objects = [];

        foreach ($lines as $line) {

            $object = new $className();

            foreach ($line as $key => $value) {
                $object->{$key} = $value;
            }
            $objects[] = $object;
        }
        return $objects;
    }

    public static function joinv2(string $className, array $tableNames, array $conditions = null)
    {
        $sqlQuery = 'SELECT DISTINCT ' . self::getTableName($className) . '.* ' .
            'FROM ' . self::getTableName($className);

        foreach ($tableNames as $tableToBind) {
            $sqlQuery .= ' INNER JOIN ' . $tableToBind[0] .
                ' on ' . $tableToBind[1] . ' = ' . $tableToBind[2];
        }
        if ($conditions) {
            $sqlQuery .= ' WHERE';
            foreach ($conditions as $condition) {
                $sqlQuery .= ' ' . $condition;
            }
        }

        $request = Model::getPDO()->prepare($sqlQuery);
        $request->execute();
        $lines = $request->fetchAll(PDO::FETCH_ASSOC);
        $objects = [];

        foreach ($lines as $line) {

            $object = new $className();

            foreach ($line as $key => $value) {
                $object->{$key} = $value;
            }
            $objects[] = $object;
        }
        return $objects;
    }
}
