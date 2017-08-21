<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>test select</title>       

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-2.1.4.min.js" type="text/javascript"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0-beta.2/angular.min.js" type="text/javascript"></script>
    </head>
    <body data-ng-app="application" data-ng-controller="homeCtrl">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Selector</h1>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            controller
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" data-ng-submit="search()">                    
                                <div class="form-group">
                                    <label class="control-label col-md-2">ระดับชั้น</label>
                                    <div class="col-md-9">
                                        <select class="form-control" data-ng-model="data.lev" data-ng-change="getContext()" data-ng-options="l.mul_level_id as l.mul_level_name for l in data.level">                            
                                        </select>
                                    </div>
                                    <div class="col-md-1" data-ng-show="switcher.swt1">
                                        <img src="/assets/img-ex-ku/ajax-loader.gif">
                                    </div>
                                </div>                        
                                <div class="form-group">
                                    <label class="control-label col-md-2">วิชา</label>
                                    <div class="col-md-9">
                                        <select class="form-control" data-ng-model="data.cate" data-ng-change="getContext()" data-ng-options="l.mul_category_id as l.mul_category_name for l in data.category">                            
                                        </select>
                                    </div>
                                    <div class="col-md-1" data-ng-show="switcher.swt2">
                                        <img src="/assets/img-ex-ku/ajax-loader.gif">
                                    </div>
                                </div>                        
                                <div class="form-group">
                                    <label class="control-label col-md-2">สาระ</label>
                                    <div class="col-md-9">
                                        <select class="form-control" data-ng-model="data.contx" data-ng-disabled="data.context.length > 0 ? false : true;" data-ng-options="l.knowledge_context_id as l.knowledge_context_name for l in data.context">                            
                                        </select>  
                                    </div>
                                    <div class="col-md-1" data-ng-show="switcher.swt3">
                                        <img src="/assets/img-ex-ku/ajax-loader.gif">
                                    </div>
                                </div>    
                                <div class="form-group">
                                    <label class="control-label col-md-2">
                                        คำค้นหา
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" data-ng-model="data.query">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-10">
                                        <button type="submit" class="btn btn-success" data-ng-disabled="switcher.swt4">ค้นหา</button>
                                        <button type="reset" class="btn btn-default" data-ng-click="data.cate='';data.contx='';data.lev=''" data-ng-disabled="switcher.swt4">ล้างค่า</button>
                                        <img src="/assets/img-ex-ku/ajax-loader.gif" data-ng-show="switcher.swt4">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">result</div>
                        <div class="panel-body">
                            {{data.result}}                        
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
        <script>
            angular.module('application', ['controller'])
                    .config(function() {
//                                alert('HELLO WORLD');
                    });

            angular.module('controller', [])
                    .controller('homeCtrl', ['$scope', '$http', function($scope, $http) {
                            $scope.switcher = {
                                swt1: false,
                                swt2: false,
                                swt3: false,
                                swt4: false
                            };                           
                            $scope.data = {};
                            $scope.switcher.swt1 = true;
                            $http.get('/api/knowledgebase/getlevel').then(function(resp) {
                                $scope.data.level = resp.data;
                                $scope.switcher.swt1 = false;
                            });
                            $scope.switcher.swt2 = true;
                            $http.get('/api/knowledgebase/getcategory/').then(function(resp) {
                                $scope.data.category = resp.data;
                                $scope.switcher.swt2 = false;
                            });

                            $scope.getContext = function() {
                                $scope.switcher.swt3 = true;
                                $http.get('/api/knowledgebase/getcontext/0/' + ($scope.data.cate ? $scope.data.cate : 0) + '/' + ($scope.data.lev ? $scope.data.lev : 0)).then(function(resp) {
                                    $scope.data.contx = '';
                                    $scope.data.context = resp.data;
                                    $scope.switcher.swt3 = false;
                                });
                            };
                            $scope.search = function() {
                                $scope.switcher.swt4 = true;
                                $scope.data.result = {};
                                $http.get('/api/knowledgebase/search' + ($scope.data.cate ? '?sid=' + $scope.data.cate : '?sid=') + ($scope.data.lev ? '&lid=' + $scope.data.lev : '&lid=') + ($scope.data.contx ? '&cid=' + $scope.data.contx : '&cid=') + ($scope.data.query ? '&q=' + $scope.data.query : '&q=')).then(function(resp) {
                                    $scope.data.result = resp.data;
                                    $scope.switcher.swt4 = false;
                                });
                            };
                        }
                    ]);
        </script>
    </body>
</html>
