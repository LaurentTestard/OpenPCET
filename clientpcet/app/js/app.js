'use strict';

var _dev = false;
// Declare app level module which depends on filters, and services
angular.module('myApp', [
  'ngRoute',
  'myApp.filters',
  'myApp.services',
  'myApp.directives',
  'myApp.controllers',
  'myApp.controllers.accueil',
  'myApp.controllers.connexion',
  'myApp.controllers.monCompte',
  'myApp.controllers.actions',
  'myApp.controllers.action',
  'myApp.controllers.gestiondedoc',
  'xeditable',
  'myApp.controllers.utilisateurs',
  'myApp.controllers.gestion',
  'myApp.controllers.notification',
  'myApp.controllers.impression',
  'myApp.controllers.historique'

]).
config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/accueil', {templateUrl: 'partials/accueil.html', controller: 'accueil'});
  $routeProvider.when('/monCompte', {templateUrl: 'partials/monCompte.html', controller: 'monCompte'});
  $routeProvider.when('/actions', {templateUrl: 'partials/actions.html', controller: 'actions'});
  $routeProvider.when('/action/:etat/:id/:modif', {templateUrl: 'partials/action.html', controller: 'action'});
  $routeProvider.when('/utilisateurs', {templateUrl: 'partials/utilisateurs.html', controller: 'utilisateurs'});
  $routeProvider.when('/gestiondedoc', {templateUrl: 'partials/gestiondedoc.html', controller: 'gestiondedoc'});
  $routeProvider.when('/impression', {templateUrl: 'partials/impression.html', controller: 'impression'});
  //$routeProvider.when('/historique', {templateUrl: 'partials/action.histrique.html', controller: 'historique'});
  $routeProvider.when('/notification', {templateUrl: 'partials/notification.html', controller: 'notification'});
  $routeProvider.otherwise({redirectTo:'/accueil'});
}]); 
