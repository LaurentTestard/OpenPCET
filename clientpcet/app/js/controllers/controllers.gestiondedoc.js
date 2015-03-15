'use strict';

/* Controllers */

angular.module('myApp.controllers.gestiondedoc', ['ui.bootstrap','angularFileUpload'])
.controller('ModalSuppressionDocument',
		[ '$scope', '$modalInstance','data', function($scope, $modalInstance, data) {
			$scope.data=data;
			$scope.textHeaderSupp = "Suppression d'un document";
			$scope.textBodySupp ="Etes-vous sur de vouloir supprimer le document "+ data.nom_document + " ?";
			$scope.bool = true;
			$scope.ok = function() {
				$modalInstance.close({b:$scope.bool,data:$scope.data});
			};
			$scope.cancel = function() {
				$modalInstance.dismiss('cancel');
			};

} ]).controller("gestiondedoc", [ '$scope', '$upload','$modal','$http','proConnexion', function($scope, $upload, $modal,$http,proConnexion) {  
	
	$scope.associer = function(d) {
		var modaleInstance = $modal.open({
			templateUrl : "partials/modal.associer.doc.html",
			controller : "AssocierDocuments",
			    resolve: {
			        d : function () {
			          return d;
			        }
		}});
		modaleInstance.result.then(function() {
		});
	};
	$scope.getDocumentList = function(){
		$http.get('/serveurpcet/index.php/document/list').success(function(data, status, headers) {
			$scope.documents = data;
			console.log("We have " +$scope.documents.length);
		});
	};
	
	$scope.changerType = function(document){
		$http.get('/serveurpcet/index.php/document/changertype/'+document.id).success(function(data, status, headers) {
			$scope.getDocumentList();
		});
	};
	
	$scope.dldoc = function(document){
		$http.get('/serveurpcet/index.php/document/'+document.id).success(function(data, status, headers) {
		
		});
	};
	
	$scope.modaleResult = false;
	
	$scope.getDocumentList();
	$scope.donneeUtilisateur={};
	proConnexion.getDonneeUtilisateur().then(function(data){
  		$scope.donneeUtilisateur = data;
  	});
	
	$scope.controlleSuppressionDocument = function(document){
		$scope.del=false;
		$http({method: 'DELETE', url: '/serveurpcet/index.php/document/delete/'+document.id}).
	    success(function(data, status, headers, config) {
	    	$scope.getDocumentList();
	    	$scope.del=true;
	    });
	};
	
	
	$scope.supprimerDocument = function(document) {
		$scope.textHeaderSupp= "TEST";
		var modaleInstance = $modal.open({
					templateUrl : "partials/modal.suppression.html",
					controller : "ModalSuppressionDocument",
					resolve : {
						data : function() {
							return document;
						}
					}
				});
		modaleInstance.result.then(function(doubledata) {
					$scope.modaleResult = doubledata.b;
					if (doubledata.b == true) {
						$scope.controlleSuppressionDocument(doubledata.data);
					}
				});
	};	
	
	
	$scope.onFileSelect = function($files) {
		$scope.percentUplaod = 0;
		    //$files: an array of files selected, each file has name, size, and type.
		    for (var i = 0; i < $files.length; i++) {
		      var file = $files[i];
		      $scope.upload = $upload.upload({
		        url: '/serveurpcet/index.php/document/ajout', //upload.php script, node.js route, or servlet url
		        // method: POST or PUT,
		        // headers: {'headerKey': 'headerValue'},
		        // withCredentials: true,
		        data: {myObj: $scope.myModelObj},
		        file: file,
		        // file: $files, //upload multiple files, this feature only works in HTML5 FromData browsers
		        /* set file formData name for 'Content-Desposition' header. Default: 'file' */
		        //fileFormDataName: myFile, //OR for HTML5 multiple upload only a list: ['name1', 'name2', ...]
		        /* customize how data is added to formData. See #40#issuecomment-28612000 for example */
		        //formDataAppender: function(formData, key, val){} //#40#issuecomment-28612000
		      }).progress(function(evt) {
		    	 $scope.percentUplaod = parseInt(100.0 * evt.loaded / evt.total);
		         //
		      }).success(function(data, status, headers, config) {
		        // file is uploaded successfully
		    	  $scope.percentUplaod = 100;
		    	  $scope.getDocumentList();
		    	  //
		      });
		      //.error(...)
		      //.then(success, error, progress); 
		    }
		    // $scope.upload = $upload.upload({...}) alternative way of uploading, sends the the file content directly with the same content-type of the file. Could be used to upload files to CouchDB, imgur, etc... for HTML5 FileReader browsers. 
		  };
		}]);  