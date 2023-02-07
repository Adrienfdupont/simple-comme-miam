<?php

require 'core/Model.php';
require 'core/Route.php';
require 'core/Controller.php';
require 'core/Message.php';

require 'models/User.php';
require 'models/Recipe.php';
require 'models/Need.php';
require 'models/Ingredient.php';
require 'models/Belong.php';
require 'models/Category.php';
require 'models/Report.php';
require 'models/Notification.php';
require 'models/Unit.php';

require 'controllers/RecipeController.php';
require 'controllers/UserController.php';
require 'controllers/IngredientController.php';
require 'controllers/CategoryController.php';
require 'controllers/ReportController.php';
require 'controllers/NotificationController.php';

session_start();

// page d'accueil
Route::call('/', [RecipeController::class, 'home']);

// requêtes ajax pour suggestions
Route::call('/home/recipes/suggestions', [RecipeController::class, 'suggestions']);
Route::call('/home/ingredients/suggestions', [IngredientController::class, 'suggestions']);
Route::call('/home/categories/suggestions', [CategoryController::class, 'suggestions']);

// requête ajax pour détecter usernames indisponibles
Route::call('/register/detect-user', [UserController::class, 'detectUser']);

// requête ajax pour signalement
Route::call('/report', [ReportController::class, 'report']);

// page de connexion et vérification des champs
Route::call('/logout', [UserController::class, 'logout']);
Route::call('/login', [UserController::class, 'login']);
Route::call('/login/verify', [UserController::class, 'verify']);

// page d'inscription et vérification des champs
Route::call('/register', [UserController::class, 'register']);
Route::call('/register/store', [UserController::class, 'store']);

// page de publication, enregistrement et modification
Route::call('/publish', [RecipeController::class, 'publish']);
Route::call('/publish/store', [RecipeController::class, 'store']);
Route::call('/publish/update', [RecipeController::class, 'update']);

// affichage du profil et modification des infos
Route::call('/profile', [UserController::class, 'profile']);
Route::call('/user/update', [UserController::class, 'update']);
Route::call('/user/update/photo', [UserController::class, 'updatePhoto']);
Route::call('/user/delete', [UserController::class, 'delete']);

// affichage de recettes individuellement ou groupées
Route::call('/recipe', [RecipeController::class, 'recipe']);
Route::call('/recipes', [RecipeController::class, 'UserRecipes']);

// affichage des recettes de l'utilisateur connecté et suppresion
Route::call('/posts', [RecipeController::class, 'posts']);
Route::call('/posts/delete', [RecipeController::class, 'delete']);
Route::call('/posts/edit', [RecipeController::class, 'edit']);

// recherche avec filtres d'une recette
Route::call('/recipes/search', [RecipeController::class, 'search']);

// affichage notifications
Route::call('/messages', [NotificationController::class, 'messages']);
Route::call('/messages/content', [NotificationController::class, 'message']);
