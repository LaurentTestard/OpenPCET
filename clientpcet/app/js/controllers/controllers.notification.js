'use strict';

/* Controllers */

angular.module('myApp.controllers.notification', []).controller('notification', [ '$scope','$http','proConnexion',function($scope, $http,proConnexion) {
  
  $('#filtreTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  });

  var allNotifications = [];
  $scope.notifications = [];
  /* Structure de $scope.notifications :
       $scope.notifications = [{
         prenom_utilisateur:'',
         nom_utilisateur:'',
         logs:[{
           date_modification:'',
           modifications:[{
             type_modification:'',
             code_action:'',
             description_modification:''
           }]
         }]
       }];
   */
  
  $http.get('/serveurpcet/index.php/pcaet/notifications/').success(function(data, status, headers) {
    // test si une notification existe déjà avec le même utilisateur
    angular.forEach(data, function(element) {
      var found = "false";
      angular.forEach($scope.notifications, function(notification) {
        if (notification.login_utilisateur == element.login_utilisateur) {
          insererLog(element, notification);
          found = "true";
          return;
        }
      });
      // sinon on insère la notification avec un nouvel utilisateur
      if (found == "false") {
        $scope.notifications.push({
          login_utilisateur: element.login_utilisateur, 
          prenom_utilisateur: element.prenom_utilisateur,
          nom_utilisateur: element.nom_utilisateur,
          logs:[{
            date_modification: element.date_modification,
            jour_modification: element.jour_modification,
            modifications: [{
            	heure_modification: element.heure_modification,
                type_modification: element.type_modification, 
                code_action: element.code_action, 
                description_modification: element.description_modification
            }]
          }]
        });
      }
    });
    allNotifications = $scope.notifications;
  });
  
  var insererLog = function(element, notification) {
    var found = "false";
    // test si une notification existe déjà avec la même date
    angular.forEach(notification.logs, function(log) {
      if (element.jour_modification == log.jour_modification) {
        log.modifications.push({
          heure_modification: element.heure_modification,
          type_modification: element.type_modification, 
          code_action: element.code_action, 
          description_modification: element.description_modification
        });
        found = "true";
        return;
      }
    });
    // sinon on insère la notification avec une nouvelle date
    if (found == "false") {
      notification.logs.push({
        date_modification: element.date_modification,
        jour_modification: element.jour_modification,
        modifications: [{
        	heure_modification: element.heure_modification,
            type_modification: element.type_modification, 
            code_action: element.code_action, 
            description_modification: element.description_modification
        }]
      });
    }
  };
  
  $scope.filtrerDureeNotifications = function() {
    if ($scope.duree == "0") {
      $scope.notifications = allNotifications;
    } else {
      var currentDate = new Date();
      $scope.notifications = [];
      angular.forEach(allNotifications, function(notification, i) {
        var tmpLogs = [];
        angular.forEach(notification.logs, function(log, j) {
          var dateFormat = new Date(log.date_modification);
          if (((currentDate - dateFormat) / 86400000) < parseInt($scope.duree)) {
            tmpLogs.push(log);
          }
        });
        if (tmpLogs.length > 0) {
          $scope.notifications.push({
            login_utilisateur: notification.login_utilisateur, 
            prenom_utilisateur: notification.prenom_utilisateur,
            nom_utilisateur: notification.nom_utilisateur,
            logs: tmpLogs
          });
        }
      });
    }
  };

  $scope.donneeUtilisateur = {};

	$scope.donneeUtilisateur = {};
	proConnexion.getDonneeUtilisateur().then(function(data){
		$scope.donneeUtilisateur = data;
	});
/* => à décommenter pour tester l'affichage
  var testData = [
    {
      login_utilisateur:'aforet', 
      prenom_utilisateur:'Anne',
      nom_utilisateur:'Forêt',
      date_modification:'2014-03-16',
      type_modification:'Ajout action',
      code_action:'D1.2',
      description_modification:'Bla bla bla'
    },
    {
      login_utilisateur: "aforet", 
      prenom_utilisateur: "Anne",
      nom_utilisateur: "Forêt",
      date_modification: "2014-03-11",
      type_modification: "Modification action",
      code_action: "D1.2",
      description_modification: "Bla bla bla"
    },
    {
      login_utilisateur: "aforet", 
      prenom_utilisateur: "Anne",
      nom_utilisateur: "Forêt",
      date_modification: "2014-02-17",
      type_modification: "Suppression action",
      code_action: "D1.2",
      description_modification: "Bla bla bla"
    },
    {
      login_utilisateur: "bdupont", 
      prenom_utilisateur: "Brigitte",
      nom_utilisateur: "Dupont",
      date_modification: "2014-03-16",
      type_modification: "Modification action",
      code_action: "A1.2",
      description_modification: "Bla bla bla"
    },
    {
      login_utilisateur: "bdupont", 
      prenom_utilisateur: "Brigitte",
      nom_utilisateur: "Dupont",
      date_modification: "2014-03-17",
      type_modification: "Ajout action",
      code_action: "D1.3",
      description_modification: "Bla bla bla"
    },
    {
      login_utilisateur: "bdupont", 
      prenom_utilisateur: "Brigitte",
      nom_utilisateur: "Dupont",
      date_modification: "2014-03-17",
      type_modification: "Modification action",
      code_action: "D1.3",
      description_modification: "Bla bla bla"
    },
    {
      login_utilisateur: "bdupont", 
      prenom_utilisateur: "Brigitte",
      nom_utilisateur: "Dupont",
      date_modification: "2014-03-17",
      type_modification: "Suppression action",
      code_action: "D1.3",
      description_modification: "Bla bla bla"
    },
    {
      login_utilisateur: "bdupont", 
      prenom_utilisateur: "Brigitte",
      nom_utilisateur: "Dupont",
      date_modification: "2014-03-18",
      type_modification: "Suppression action",
      code_action: "D1.4",
      description_modification: "Bla bla bla"
    }
  ];
  
  var testAffichage = function(data) {
    // test si une notification existe déjà avec le même utilisateur
    angular.forEach(data, function(element) {
      var found = "false";
      angular.forEach($scope.notifications, function(notification) {
        if (notification.login_utilisateur == element.login_utilisateur) {
          insererLog(element, notification);
          found = "true";
          return;
        }
      });
      // sinon on insère la notification avec un nouvel utilisateur
      if (found == "false") {
        $scope.notifications.push({
          login_utilisateur: element.login_utilisateur, 
          prenom_utilisateur: element.prenom_utilisateur,
          nom_utilisateur: element.nom_utilisateur,
          logs:[{
            date_modification: element.date_modification,
            modifications: [{
                type_modification: element.type_modification, 
                code_action: element.code_action, 
                description_modification: element.description_modification
            }]
          }]
        });
      }
    });
    allNotifications = $scope.notifications;
  };
  
  testAffichage(testData);
*/
 
} ]);
