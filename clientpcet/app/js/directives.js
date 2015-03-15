'use strict';
/* Directives */
angular
		.module('myApp.directives', [])
		.directive('appVersion', [ 'version', function(version) {
			return function(scope, elm, attrs) {
				elm.text(version);
			};
		} ])
		.directive(
				'multiSelect',
				function($q) {
					return {
						restrict : 'E',
						require : 'ngModel',
						scope : {
							selectedLabel : "@",
							availableLabel : "@",
							displayAttr : "@",
							available : "=",
							model : "=ngModel"
						},

						 template: '<div class="multiSelect">' + 
			                '<div class="select">' + 
			                '<div>'+
			                  '<label class="control-label" for="multiSelectSelected">{{ selectedLabel }} ' +
			                      '({{ model.length }})</label>' +
			                      '</div>' + 
			                  '<select id="currentRoles" width="200" style="width: 200px" ng-model="selected.current" multiple ' + 
			                      'class="pull-left" ng-options="e as e[displayAttr] for e in model">' + 
			                      '</select>' + 
			                '</div>' + 
			                '<div class="select buttons">' + 
			                  '<button class="btn mover left btn-primary" ng-click="add()" title="Add selected" ' + 
			                      'ng-disabled="selected.available.length == 0">' + 
			                      'AJOUTER Gauche' + 
			                  '</button>' + 
			                  '<button class="btn mover right btn-primary" ng-click="remove()" title="Remove selected" ' + 
			                      'ng-disabled="selected.current.length == 0">' + 
			                    'AJOUTER Droite' + 
			                  '</button>' +
			                '</div>' + 
			                '<div class="select">' +
			                '<div>'+
			                  '<label class="control-label" for="multiSelectAvailable">{{ availableLabel }} ' +
			                      '({{ available.length }})</label>' +
			                      '</div>' + 
			                  '<select id="multiSelectAvailable" width="200" style="width: 200px" ng-model="selected.available" multiple ' +
			                      'ng-options="e as e[displayAttr] for e in available"></select>' +
			                '</div>' +
			              '</div>',
						link : function(scope, elm, attrs) {
							scope.selected = {
								available : [],
								current : []
							};

							/*
							 * Handles cases where scope data hasn't been
							 * initialized yet
							 */
							var dataLoading = function(scopeAttr) {
								var loading = $q.defer();
								if (scope[scopeAttr]) {
									loading.resolve(scope[scopeAttr]);
								} else {
									scope.$watch(scopeAttr, function(newValue,
											oldValue) {
										if (newValue !== undefined)
											loading.resolve(newValue);
									});
								}
								return loading.promise;
							};

							/*
							 * Filters out items in original that are also in
							 * toFilter. Compares by reference.
							 */
							var filterOut = function(original, toFilter) {
								var filtered = [];
								angular
										.forEach(
												original,
												function(entity) {
													var match = false;
													for (var i = 0; i < toFilter.length; i++) {
														if (toFilter[i][attrs.displayAttr] == entity[attrs.displayAttr]) {
															match = true;
															break;
														}
													}
													if (!match) {
														filtered.push(entity);
													}
												});
								return filtered;
							};

							scope.refreshAvailable = function() {
								scope.available = filterOut(scope.available,
										scope.model);
								scope.selected.available = [];
								scope.selected.current = [];
							};

							scope.add = function() {
								scope.model = scope.model
										.concat(scope.selected.available);
								scope.refreshAvailable();
							};
							scope.remove = function() {
								scope.available = scope.available
										.concat(scope.selected.current);
								scope.model = filterOut(scope.model,
										scope.selected.current);
								scope.refreshAvailable();
							};

							$q.all(
									[ dataLoading("model"),
											dataLoading("available") ]).then(
									function(results) {
										scope.refreshAvailable();
									});
						}
					};
				}).directive('myMenuhorizontale', function() {
			return {
				restrict : 'E',
				scope : {
					endurl : '=',
					starturl : '=',
					ongletselect : '=',
					onglets : '='
				},
				templateUrl : 'partials/d_menu-horizontale.html'
			};
		});

function Ctrl($scope) {
	$scope.test = "hello";
}
