<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
  <head>
    <meta charset="UTF-8">
    <title></title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/ng-prettyjson.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="/assets/js_custom/AngularJs.js" type="text/javascript"></script>
    <script src="/assets/js_custom/ng-prettyjson.min.js"></script>
    <script src="/assets/js_custom/ng-prettyjson-tmpl.js"></script>
  </head>
  <body>
    <div class="col-md-12" data-ng-app="app" data-ng-controller="apiCtrl as ac">
      <div class="col-md-12">
        <h1 class="text-header">API TEST <small>BY TRUEPLOOKPANYA.COM</small></h1>
        <hr>
        <form>
          <div class="col-md-6">
            <div class="panel-danger panel">
              <div class="panel-heading">
                Request
              </div>
              <div class="panel-body">

                <div class="form-group">
                  URL for request:
                  <input class="form-control" data-ng-model="url" placeholder="URL for test" type="text">
                </div>
                <div class="form-group well">
                  <div class="row">
                    <div class="col-md-12">
                      Parameter
                    </div>
                    <div class="col-md-6">
                      <input class="form-control" data-ng-model="params.name" placeholder="name of param" type="text">
                    </div>
                    <div class="col-md-6">
                      <input class="form-control" data-ng-model="params.value" ngEnter="addParams()" placeholder="value" type="text">
                    </div>
                    <br>
                    <div class="col-md-12 btn-right">
                      <br>
                      <button class="btn btn-default" data-ng-show="edVis" data-ng-click="ac.addParams()">Add Params</button>
                      <button class="btn btn-default" data-ng-hide="edVis" data-ng-click="params = ''; edVis = true">Save</button>
                    </div>
                    <div class="col-md-12">
                      <table  class="table table-responsive table-striped table-hover">
                        <thead>
                          <tr>
                            <th>Parameter</th>
                            <th>Value</th>
                            <th>action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr data-ng-repeat="l in data">
                            <td>
                              <p data-ng-bind="l.name" ></p>
                            </td>
                            <td>
                              <p data-ng-bind="l.value" ></p>
                            </td>
                            <td>
                              <a class="btn btn-danger btn-sm"  data-ng-click="ac.deleteParams($index)"><i class="glyphicon glyphicon-remove-circle"></i></a>
                              <a class="btn btn-warning btn-sm"  data-ng-click="ac.editerer(l)"><i class="glyphicon glyphicon-cog"></i></a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="panel panel-info">
              <div class="panel-heading">
                Action
              </div>
              <div class="panel-body">
                <div class="col-md-3">
                  <button class="btn btn-info btn-block" style="height: 100px" data-ng-click="geter()"><h2>GET</h2></button>
                </div>
                <div class="col-md-3">
                  <button class="btn btn-primary btn-block" style="height: 100px" data-ng-click="poster()"><h2>POST</h2></button>

                </div>
                <div class="col-md-3">
                  <button class="btn btn-danger btn-block" data-ng-click="deleter()" style="height: 100px"><h2>DELETE</h2></button>

                </div>
                <div class="col-md-3">
                  <button class="btn btn-warning btn-block" data-ng-click="puter()" style="height: 100px"><h2>PUT</h2></button>
                </div>
                <div class="col-md-12">
                  <br>
                  <button class="btn btn-default btn-block" data-ng-click="resault = {}"><h1>CLEAR Result</h1></button>
                </div>
              </div>
            </div>
          </div>
        </form>
        <div class="col-md-12">
          Html Result:
          <div class="well" dynamic="resault">
          </div>
          Json Result:
          <pretty-json json="resault"></pretty-json>
        </div>
      </div>
    </div>
    <script>
              angular.module('app', ['ngPrettyJson']).config(function($httpProvider) {
      delete $httpProvider.defaults.headers.common['X-Requested-With'];
              $httpProvider.defaults.headers.post['Accept'] = 'application/json, text/javascript, */*';
              $httpProvider.defaults.headers.post['Content-Type'] = 'application/json; charset=utf-8';
              $httpProvider.defaults.headers.post['Access-Control-Max-Age'] = '1728000';
              $httpProvider.defaults.headers.common['Access-Control-Max-Age'] = '1728000';
              $httpProvider.defaults.headers.common['Accept'] = 'application/json, text/javascript, */*';
              $httpProvider.defaults.headers.common['Content-Type'] = 'application/json; charset=utf-8';
              $httpProvider.defaults.useXDomain = true;
      })
              .controller('apiCtrl', function($scope, $http, $Data) {
              $scope.data = [];
                      $scope.params = {};
                      $scope.resault = {};
                      $scope.edVis = true;
                      $scope.url = "";
                      $scope.conv = [];
                      var ctrler = this;
                      $scope.converter_value = function() {
                      var arr = {};
                              $scope.conv = {};
                              angular.forEach($scope.data, function(val) {
//                              $scope.conv[val.name] = JSON.parse(val.value);
                              $scope.conv[val.name] = val.value;
                              });
                      };
                      ctrler.addParams = function() {
                      if ($scope.params.name !== undefined && $scope.params.value !== undefined) {
                      $scope.data.push($scope.params);
                              $scope.params = {};
                      }
                      };
                      ctrler.deleteParams = function(index) {
                      $scope.data.splice(index, 1);
                      };
                      ctrler.editerer = function(list){
                      $scope.edVis = false;
                              $scope.params = list;
                      };
                      $scope.poster = function() {
                      ctrler.addParams();
                              $scope.converter_value();
                              $.post($scope.url, $scope.conv, function(resp) {
                              $scope.resault = resp;
                                      $scope.$apply();
                              });
//                  OR
//                  $http.post($scope.url,$scope.conv).then(function(resp){
//                    $scope.resault = resp;
//                  }, function(resp) {
//                    $scope.resault = resp;
//                  });
                      };
                      $scope.geter = function() {
                      $Data.get($scope.url).then(function(resp) {
                      $scope.resault = resp;
                      }, function(resp) {
                      $scope.resault = resp;
                      });
                      };
                      $scope.puter = function() {
                      ctrler.addParams();
                              $scope.converter_value();
                              $Data.put($scope.url, $scope.conv).then(function(resp) {
                      $scope.resault = resp;
                      }, function(resp) {
                      $scope.resault = resp;
                      });
                      };
                      $scope.deleter = function() {
                      $Data.delete($scope.url, $scope.data).then(function(resp) {
                      $scope.resault = resp;
                      }, function(resp) {
                      $scope.resault = resp;
                      });
                      };
              })
              .factory("$Data", ['$http', '$location',
                      function($http, $q, $location) {
                      var obj = {};
                              obj.get = function(q) {
                              return $http.get(q).then(function(results) {
                              return results.data;
                              });
                              };
                              obj.post = function(q, object) {
                              return $http.post(q, object).then(function(results) {
                              return results.data;
                              });
                              };
                              obj.put = function(q, object) {
                              return $http.put(q, object).then(function(results) {
                              return results.data;
                              });
                              };
                              obj.delete = function(q) {
                              return $http.delete(q).then(function(results) {
                              return results.data;
                              });
                              };
                              return obj;
                      }
              ])
              .directive('dynamic', function($compile) {
              return {
              restrict: 'A',
                      replace: true,
                      link: function(scope, ele, attrs) {
                      scope.$watch(attrs.dynamic, function(html) {
                      ele.html(html);
                              $compile(ele.contents())(scope);
                      });
                      }
              };
              })
              .directive('ngEnter', function() {
              return function(scope, element, attrs) {
              element.bind("keydown keypress", function(event) {
              if (event.which === 13) {
              scope.$apply(function() {
              scope.$eval(attrs.ngEnter);
              });
                      event.preventDefault();
              }
              });
              };
              });
    </script>
  </body>
</html>
