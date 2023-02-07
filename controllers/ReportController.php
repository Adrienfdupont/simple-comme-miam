<?php

class ReportController extends Controller
{
    // insérer dans la bdd le signalement d'un utilisateur à
    // propos d'une recette
    public static function report()
    {
        // on vérifie que l'utilisateur est connecté pour pouvoir signaler
        if (isset($_SESSION['id'])) {

            // on vérifie qu'il n'y a pas déjà de signalement lié
            // à cet utilisateur sur cette recette
            if (!Report::where('recipe_id = ' . $_REQUEST['recipe'] . ' AND user_id = ' . $_SESSION['id'])) {
                $report = new Report;
                $report->reason = $_REQUEST['reason'];
                $report->was_considered = 0;
                $report->recipe_id = $_GET['recipe'];
                $report->user_id = $_SESSION['id'];
                $report->save();

                echo 'Votre signalement a bien été reçu.';
            } else {
                echo 'Nous avons déjà reçu de vous un signalement à propos de cette recette.';
            }
        } else {
            // sinon on redirige vers la page login
            header('location: /login');
        }
    }
}
