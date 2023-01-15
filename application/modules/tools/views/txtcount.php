<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Text Count by TruePlookpanya</title>        
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>        
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
    </head>
    <body data-ng-app="app" data-ng-controller="HomeCtrl">                
        <div class="container">
            <div class="row">
                <div class="col-md-12"><h1 class="page-header">Text count by Trueplookpanya</h1></div>
                <div class="col-md-6" data-ng-class="{'has-error':!data.classT,'has-success':data.classT}">
                    <textarea class="form-control" data-ng-model="data.txta" style="height: 300px">
                        
                    </textarea>
                </div>
                <div class="col-md-6 well">                    
                    <h1 class="page-header" data-ng-bind="(data.txta.length + ' Character')"></h1>
                    <div class="col-md-12">
                        <label>setting max character</label>
                        <input type="number" min="0" class="form-control" placeholder="MAX length" data-ng-model="data.maxy">
                        <div class="row">
                            <br>
                            <div class="col-md-6"><button data-ng-click="(data.maxy = data.maxy - 1)" class="btn btn-danger btn-block btn-lg">-</button></div>
                            <div class="col-md-6"><button data-ng-click="(data.maxy = data.maxy + 1)" class="btn btn-primary btn-block btn-lg">+</button></div>                                                
                        </div>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-12">
                        <span data-ng-hide="data.classT" class="alert alert-danger alert-block img-responsive">MAXIMUM TEXT</span>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <!--BEGIN WEB STAT Truehit CODE-->
                    <!--BEGIN WEB STAT CODE-->
                    <script type="text/javascript"> __th_page = "truelittlemonk<?echo isset($pagename)?'_'.$pagename:'';?>";</script>
                    <script type="text/javascript" src="http://hits.truehits.in.th/data/t0029829.js"></script>
                    <noscript> 
                    <a target="_blank" href="http://truehits.net/stat.php?id=t0029829"><img src="http://hits.truehits.in.th/noscript.php?id=t0029829" alt="Thailand Web Stat" border="0" width="14" height="17" /></a> 
                    <a target="_blank" href="http://truehits.net/">Truehits.net</a> 
                    </noscript>
                    <!-- END WEBSTAT CODE -->
                    <script type="text/javascript" language="javascript1.1" src="http://tracker.stats.in.th/tracker.php?uid=34348"></script>
                    <noscript>
                    <a target="_blank" href="http://www.stats.in.th/">www.Stats.in.th</a>
                    </noscript>
                    <script type="text/javascript">
                                var _gaq = _gaq || [];
                                _gaq.push(['_setAccount', 'UA-24025527-1']);
                                _gaq.push(['_trackPageview']);
                                (function() {
                                    var ga = document.createElement('script');
                                    ga.type = 'text/javascript';
                                    ga.async = true;
                                    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                                    var s = document.getElementsByTagName('script')[0];
                                    s.parentNode.insertBefore(ga, s);
                                })();
                    </script>
                    <!-- END WEBSTAT Truehit CODE -->
                </div>
            </div>
        </div>
    </body>
    <script>
        angular.module('app', ['Controller']);
        angular.module('Controller', [])
                .controller('HomeCtrl', function($scope) {
                    $scope.data = {
                        maxy: 0,
                        txta: '',
                        classT: true
                    };
                    $scope.checklength = function(data) {
                        if ($scope.data.maxy > 0) {
                            if ($scope.data.txta.length > $scope.data.maxy) {
                                if ($scope.data.classT) {
                                    $scope.data.classT = false;
                                }
                            } else {
                                if (!$scope.data.classT) {
                                    $scope.data.classT = true;
                                }
                            }
                        }
                    };
                    $scope.$watch('data.maxy', function(data) {
                        $scope.checklength();
                    });
                    $scope.$watch('data.txta', function(data) {
                        $scope.checklength();
                    });
                });
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>
    </script>
</html>
