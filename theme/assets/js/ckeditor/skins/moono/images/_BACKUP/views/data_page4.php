<?php
$this->template->add_javascript('assets/js_custom/AngularJs.js');
$this->template->add_stylesheet('assets/css/custom_v2015.css');
$this->template->add_stylesheet('assets/css/bootstrap/css/bootstrap.css');
$this->template->add_javascript('assets/css/bootstrap/js/bootstrap.min.js');
$this->template->add_stylesheet('assets/css/pattern.css');
$this->template->add_stylesheet('assets/css/card-hover.css');
$this->template->add_stylesheet('assets/knowledgeV2/select/select.css');
$this->template->add_stylesheet('assets/custom-scrollbar/jquery.mCustomScrollbar.css');
$this->template->add_javascript('assets/custom-scrollbar/jquery.mCustomScrollbar.js');
$this->template->add_stylesheet('assets/knowledgeV2/css/knowledgeV2.css');
?>
<main class="main home-page campaign-module">
    <?php $this->load->view('global/block/header_section/_knowledgeV2'); ?>
</main>
<?php
if ($IE_version == "IE>8") {
    echo $isDetect;
}
?>
<style>
    .text-limit-2{
        height: 39px;
        overflow: hidden;
    }
    .text-limit{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }
    .text-limit-2{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-height: 80px;
    }

    .sp-border-form-red{
        border: 2.5px solid #E02125;
        border-radius: 7px;
    }
    .sp-select-red{
        border: 1px solid transparent;
        border-radius: 7px;
        box-shadow: 0px 2px 0px #C9302C;
        background: transparent;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background: url('/assets/img-ex-ku/arrow-down_1.png')  96% / 4% no-repeat #E02125;
        color:white;
    }

    .sp-select-red option{
        background-color: white;
        color: black;
        padding-top: 2px;
        padding-left: 5px;
    }

    .sp-button-red{
        border: 1px solid transparent;
        background-color: #E02125;
        color: white;
        padding: 3px 20px;
        box-shadow: 0px 2px 0px #C9302C;
    }
    .css-crop-150{
        border-radius: 5px;
    }
</style>
<script type="text/javascript">
    var app = angular.module('app', []);
            app.config(function ($compileProvider) {
            $compileProvider.debugInfoEnabled(false);
                    $compileProvider.directive('compile', function ($compile) {
                    return function (scope, element, attrs) {
                    scope.$watch(
                            function (scope) {
                            return scope.$eval(attrs.compile);
                            },
                            function (value) {
                            element.html(value);
                                    $compile(element.contents())(scope);
                            }
                    );
                    };
                    });
                    //config
            });
            app.directive('ngThumb', ['$window', function ($window) {
            var helper = {
            support: !!($window.FileReader && $window.CanvasRenderingContext2D),
                    isFile: function (item) {
                    return angular.isObject(item) && item instanceof $window.File;
                    },
                    isImage: function (file) {
                    var type = '|' + file.type.slice(file.type.lastIndexOf('/') + 1) + '|';
                            return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== - 1;
                    }
            };
                    return {
                    restrict: 'A',
                            template: '<canvas/>',
                            link: function (scope, element, attributes) {
                            if (!helper.support)
                                    return;
                                    var params = scope.$eval(attributes.ngThumb);
                                    if (!helper.isFile(params.file))
                                    return;
                                    if (!helper.isImage(params.file))
                                    return;
                                    var canvas = element.find('canvas');
                                    var reader = new FileReader();
                                    reader.onload = onLoadFile;
                                    reader.readAsDataURL(params.file);
                                    function onLoadFile(event) {
                                    var img = new Image();
                                            img.onload = onLoadImage;
                                            img.src = event.target.result;
                                    }

                            function onLoadImage() {
                            var width = params.width || this.width / this.height * params.height;
                                    var height = params.height || this.height / this.width * params.width;
                                    canvas.attr({width: width, height: height});
                                    canvas[0].getContext('2d').drawImage(this, 0, 0, width, height);
                            }
                            }
                    };
            }]);
            app.directive('ngEnter', function () {
            return function (scope, element, attrs) {
            element.bind("keydown keypress", function (event) {
            if (event.which === 13) {
            scope.$apply(function () {
            scope.$eval(attrs.ngEnter);
            });
                    event.preventDefault();
            }
            });
            };
            });
            app.directive('dynamic', function ($compile) {
            return {
            restrict: 'A',
                    replace: true,
                    link: function (scope, ele, attrs) {
                    scope.$watch(attrs.dynamic, function (html) {
                    ele.html(html);
                            $compile(ele.contents())(scope);
                    });
                    }
            };
            });
            
            
            app.run(function () {
            //constructer

            });
            app.factory('api', ['$http', function ($http) {
            var func = {
            getLevel: function (f) {
            $http.get('/api/knowledgebase/getlevel').then(f);
            },
                    getActivities: function (id, f) {
                    $http.get('/api/knowledgebase/getactivities/' + id + '?offset=1&start=0&end=40').then(f);
                    },
                    getChildhood: function (id, f) {
                    $http.get('/api/knowledgebase/getchildhood/' + id + '&s=0&e=40').then(f);
                    },
                    getEduImg: function (f) {
                    $http.get('/api/knowledgebase/geteduimg').then(f);
                    },
                    getEduSong: function (f) {
                    $http.get('/api/knowledgebase/getedusong').then(f);
                    },
                    searchActivities: function (id, data, f) {
                    $http({
                    method: 'GET',
                            url: '/api/knowledgebase/searchActivities/' + id + '?',
                            params: data
                    }).then(f);
                    },
                    searchChildhood: function (data, f) {
                    $http({
                    method: 'GET',
                            url: '/api/knowledgebase/getchildhood',
                            params: data
                    }).then(f);
                    },
                    searchLoadmoreAll: function (data, f) {
                    $http({
                    method: 'GET',
                            url: '/api/knowledgebase/loadmore/?type=activity',
                            params: data
                    }).then(f);
                    },
                    getInfoGraphic: function (f) {
                    $http({
                    method: 'GET',
                            url: '/api/knowledgebase/getInfographic'
                    }).then(f);
                    },
                    getBannerZone: function (zone_id, f) {
                    $http({
                    method: 'GET',
                            url: '/api/banner/zone/' + zone_id
                    }).then(f);
                    },
                    getBannerV3: function (zone_id, f) {
                    $http({
                    method: 'GET',
                            url: '/api/banner/bannerV3/' + zone_id
                    }).then(f);
                    },
                    file_exits: function (url) {
                    var http = new XMLHttpRequest();
                            http.open('HEAD', url, false);
                            http.send();
                            return http.status != 404;
                    }
            };
                    return func;
            }]);
            app.factory('hist', function () {
            var func = {
            push_url: function (state, title, url) {
            history.pushState(state, title, url);
            },
                    replace_url: function (state, title, url) {
                    history.replaceState(state, title, url);
                    },
                    back: function () {
                    history.go( - 1);
                    },
                    next: function () {
                    history.go(1);
                    },
                    is: function () {
                    history.state;
                    }
            };
                    return func;
            });
            app.factory('level', function () {
            var level_name = {
            '': 'ปฐมวัย',
                    01: 'อ.1',
                    02: 'อ.2',
                    03: 'อ.3',
                    10: 'ประถมต้น',
                    11: 'ป.1',
                    12: 'ป.2',
                    13: 'ป.3',
                    20: 'ประถมปลาย',
                    21: 'ป.4',
                    22: 'ป.5',
                    23: 'ป.6',
                    30: 'มัธยมต้น',
                    31: 'ม.1',
                    32: 'ม.2',
                    33: 'ม.3',
                    40: 'มัธยมปลาย',
                    41: 'ม.4',
                    42: 'ม.5',
                    43: 'ม.6'
            };
                    var func = {
                    get: function (id) {
                    return level_name[id];
                    }
                    };
                    return func;
            });
            app.factory('category', function () {
            var category_name = {
            '': 'ทั้งหมด',
                    10000: 'ความรู้ปฐมวัย',
                    9200: 'สื่อน่ารู้',
                    9300: 'กิจกรรมนอกห้องเรียน',
                    9100: 'ลูกเสือ-เนตรนารี',
            };
                    var func = { get: function (id) {
                    return category_name[id];
                    }
                    };
                    return func;
            });
            app.controller('searchCtrl', function ($timeout, $scope, api, $log, hist, level) {
            var here = this;
                    //alert(level.get(3));
                    angular.extend($scope, {
                    level_list:
                            [
                            {level_id: '', level_name: 'ทั้งหมด'},
                            {level_id: 01, level_name: 'อ.1'},
                            {level_id: 02, level_name: 'อ.2'},
                            {level_id: 03, level_name: 'อ.3'},
                            {level_id: 11, level_name: 'ป.1'},
                            {level_id: 12, level_name: 'ป.2'},
                            {level_id: 13, level_name: 'ป.3'},
                            {level_id: 21, level_name: 'ป.4'},
                            {level_id: 22, level_name: 'ป.5'},
                            {level_id: 23, level_name: 'ป.6'},
                            {level_id: 31, level_name: 'ม.1'},
                            {level_id: 32, level_name: 'ม.2'},
                            {level_id: 33, level_name: 'ม.3'},
                            {level_id: 41, level_name: 'ม.4'},
                            {level_id: 42, level_name: 'ม.5'},
                            {level_id: 43, level_name: 'ม.6'}
                            ]
                            ,
                            category_list:
                            [
                            {category_id: '', category_name: 'ทั้งหมด'},
                            {category_id: 10000, category_name: 'ความรู้ปฐมวัย'},
                            {category_id: 9200, category_name: 'สื่อน่ารู้'},
                            {category_id: 9300, category_name: 'กิจกรรมนอกห้องเรียน'},
                            {category_id: 9100, category_name: 'ลูกเสือ-เนตรนารี'}
                            ]
                            ,
                            data: {
                            level_id: '<?= ($this->input->get('level_id')) ?>',
                                    category_id: '<?= (!empty($_GET['category_id']) ? $_GET['category_id'] : '') ?>',
                                    q: '<?= (isset($_GET['q']) ? $_GET['q'] : '') ?>'
                            },
                            list: {
                            activi_9100: [],
                                    activi_9200: [],
                                    activi_9300: [],
                                    activi_child: [],
                                    activi_all: [],
                            },
                            loadmore: false,
                            loader1: false,
                            loader2: false,
                            loader3: false,
                            loader4: false,
                            notfound1: false,
                            notfound2: false,
                            notfound3: false,
                            notfound4: false,
                            infog: ''
                    });
                    
                    $scope.select_level_data = function(resp) {
                        if(resp === 10000){
                            $scope.level_list = [
                            {level_id: '', level_name: 'ทั้งหมด'},
                            {level_id: 01, level_name: 'อ.1'},
                            {level_id: 02, level_name: 'อ.2'},
                            {level_id: 03, level_name: 'อ.3'},
                            ];
                        }else{
                            $scope.level_list = [
                            {level_id: '', level_name: 'ทั้งหมด'},
                            {level_id: 01, level_name: 'อ.1'},
                            {level_id: 02, level_name: 'อ.2'},
                            {level_id: 03, level_name: 'อ.3'},
                            {level_id: 11, level_name: 'ป.1'},
                            {level_id: 12, level_name: 'ป.2'},
                            {level_id: 13, level_name: 'ป.3'},
                            {level_id: 21, level_name: 'ป.4'},
                            {level_id: 22, level_name: 'ป.5'},
                            {level_id: 23, level_name: 'ป.6'},
                            {level_id: 31, level_name: 'ม.1'},
                            {level_id: 32, level_name: 'ม.2'},
                            {level_id: 33, level_name: 'ม.3'},
                            {level_id: 41, level_name: 'ม.4'},
                            {level_id: 42, level_name: 'ม.5'},
                            {level_id: 43, level_name: 'ม.6'}
                            ]
                        }
                        
                        // use $scope.selectedItem.code and $scope.selectedItem.name here
                        // for other stuff ...
                     }
                    
                    api.getEduImg(function (resp) {
                    $scope.list.eduimg = resp.data;
                    });
                    api.getEduSong(function (resp) {
                    $scope.list.edusong = resp.data;
                    });
                    api.getLevel(function (resp) {
                    $scope.list.level = resp.data;
                    });
                    api.getInfoGraphic(function (resp) {
                    $scope.list.infogr = resp.data;
                    });
//                        api.getBannerZone(10, function(resp) {
//                            $scope.list.banner = resp.data;
//                        });
                    api.getBannerV3(10, function (resp) {
                    $scope.list.banner = resp.data;
                    });
                    //console.log($scope.data.cat_id);
                    angular.extend(here, {
<?php
switch ($_GET['page']) {
    case 'media-news' : echo 'page: 2,';
        break;
    case 'media-prototype' : echo 'page: 3,';
        break;
    case 'media-portfolio' : echo 'page: 4,';
        break;
    case 'media-child' : echo 'page: 1,';
        break;
    default : echo 'page: 1,';
        break;
}
?>
                    submitV2: function () {
                    var urlX = location.pathname + '?level_id=' + ($scope.data.level_id ? $scope.data.level_id : '') + '&category_id=' + ($scope.data.category_id ? $scope.data.category_id : 0) + '&q=' + ($scope.data.q ? $scope.data.q : '');
                            window.location.assign(urlX);
                    },
                            submit: function () {
                            $scope.loader1 = true;
                                    $scope.loader2 = true;
                                    $scope.loader3 = true;
                                    $scope.loader4 = true;
                                    $scope.loader = true;
                                    $scope.loadOpa = true;
                                    $scope.notfound1 = false;
                                    $scope.notfound2 = false;
                                    $scope.notfound3 = false;
                                    $scope.notfound4 = false;
                                    angular.element('.list-from-server').remove();
                                    $scope.elm_server = true;
                                    api.searchActivities(9100, $scope.data, function (resp) {
                                    if (resp.data.length === 0) {
                                    $scope.notfound1 = true;
                                    }

                                    if (resp.data.length < 8) {
                                    $scope.loader1 = true;
                                    } else {
                                    $scope.loader1 = false;
                                    }

                                    $scope.list.activi_9100 = resp.data;
                                            $scope.loadOpa = false;
                                    });
                                    api.searchActivities(9200, $scope.data, function (resp) {
                                    if (resp.data.length === 0) {
                                    $scope.notfound2 = true;
                                    }
                                    if (resp.data.length < 8) {
                                    $scope.loader2 = true;
                                    } else {
                                    $scope.loader2 = false;
                                    }
                                    $scope.list.activi_9200 = resp.data;
                                            $scope.loadOpa = false;
                                    });
                                    api.searchActivities(9300, $scope.data, function (resp) {
                                    if (resp.data.length === 0) {
                                    $scope.notfound3 = true;
                                    }

                                    if (resp.data.length < 8) {
                                    $scope.loader3 = true;
                                    } else {
                                    $scope.loader3 = false;
                                    }

                                    $scope.list.activi_9300 = resp.data;
                                            $scope.loadOpa = false;
                                    });
                                    api.searchChildhood($scope.data, function (resp) {
                                    // console.log($scope.data);
                                    $scope.data.s = 0;
                                            $scope.data.e = 40;
                                            if (resp.data.length === 0) {
                                    $scope.notfound4 = true;
                                    }

                                    if (resp.data.length < 40) {
                                    $scope.loader4 = true;
                                    } else {
                                    $scope.loader4 = false;
                                    }

                                    $scope.list.activi_child = resp.data;
                                            $scope.loadOpa = false;
                                    });
                                    api.searchLoadmoreAll($scope.data, function (resp) {
                                    // console.log($scope.data);
                                    if (resp.data.length === 0) {
                                    $scope.notfound1 = true;
                                    }

                                    if (resp.data.length < 8) {
                                    $scope.loader1 = true;
                                    } else {
                                    $scope.loader1 = false;
                                    }

                                    $scope.list.activi_all = resp.data;
                                            $scope.loadOpa = false;
                                    });
                                    hist.replace_url($scope.data, 'สื่อพัฒนานอกระบบ|กิจกรรมห้องเรียน|ลูกเสือ-เนตรนารี|', location.pathname + '?level_id=' + ($scope.data.level_id ? $scope.data.level_id : '') + '&category_id=' + ($scope.data.category_id ? $scope.data.category_id : '') + '&q=' + ($scope.data.q ? $scope.data.q : ''));
                                    document.title = 'สื่อพัฒนานอกระบบ|กิจกรรมห้องเรียน|ลูกเสือ-เนตรนารี|' + (level.get($scope.data.level_id)) + ($scope.data.q ? '|' + $scope.data.q : '') + '|ความรู้และกิจกรรมเสริมทักษะ';
                            },
                            isPage: function (p) {
                            if (here.page === p)
                            {
                            return true;
                            }
                            else {
                            return false;
                            }
                            },
                            setPage: function (num) {
                            here.page = num;
                            },
                            getOffsetAll: function (id, list) {
                            $scope.loadmore = true;
                                    $scope.loadOpa = true;
                                    $scope.data.offset = true;
                                    $scope.data.start = ($('.' + id).length);
                                    $scope.data.end = 8;
                                    $scope.loop = 0;
                                    api.searchLoadmoreAll($scope.data, function (resp) {
                                    if (resp.data.other === null) {
                                    console.log('not found data...');
                                            $scope.loop = 0;
                                            
                                            $scope.loader1 = true;
                                            $scope.loadOpa = false;
                                            $scope.loadmore = false;
                                            console.log($scope);
                                    } else{
                                    $scope.loop = resp.data.other.length;
                                            angular.forEach(resp.data.other, function (val, key) {
                                            list.push(val);
                                            });
                                            //console.log("id:" + id);
                                            console.log($scope.loop);
                                            if ($scope.loop < 8) {
                                            id === "list-data-1" ? $scope.loader1 = true : "";
                                            $scope.loadOpa = false;
                                            $scope.loadmore = false;
                                    } else {
                                            $scope.loadOpa = false;
                                            $scope.loadmore = false;
                                    }

                                    }
                                    });
                            },
                            getOffset: function (ac_id, id, list) {
                            $scope.loadmore = true;
                                    $scope.loadOpa = true;
                                    $scope.data.offset = 1;
                                    $scope.data.start = ($('.' + id).length);
                                    $scope.data.end = 8;
                                    $scope.loop = 0;
                                    api.searchActivities(ac_id, $scope.data, function (resp) {
                                        
                                     
                                    if (resp.data.length === 0) {
                                    console.log('not found data...');
                                    }

                                    angular.forEach(resp.data, function (val, key) {
                                    list.push(val);
                                    });
                                            $scope.loop = resp.data.length;
                                            //console.log("id:" + id);
                                            //console.log($scope.loop);
                                    if ($scope.loop < 8) {

                                    id === "list-data-1" ? $scope.loader1 = true : "";
                                            id === "list-data-2" ? $scope.loader2 = true : "";
                                            id === "list-data-3" ? $scope.loader3 = true : "";
                                            $scope.loadOpa = false;
                                            $scope.loadmore = false;
                                    } else {
                                    $scope.loadOpa = false;
                                            $scope.loadmore = false;
                                    }


                                    });
                            },
                            getOffsetchild: function (id, list) {
                            $scope.loadmore = true;
                                    //$scope.loader1 = true;
                                    $scope.data.s = ($('.' + id).length);
                                    $scope.data.e = 8;
                                    $scope.loop = 0;
                                    api.searchChildhood($scope.data, function (resp) {
                                    if (resp.data.length === 0) {
                                    console.log('not found data...');
                                    }
                                    $scope.loop = resp.data.length;
                                    //console.log($scope.data.s);
                                            angular.forEach(resp.data, function (val, key) {
                                            list.push(val);
                                            });
                                    if ($scope.loop < 8) {
                                    $scope.loader4 = true;
                                            $scope.loadOpa = false;
                                            $scope.loadmore = false;
                                    } else {
                                    $scope.loadOpa = false;
                                            $scope.loadmore = false;
                                    }


                                    });
                            }
                    });
            }
            );</script>
<?php
$srcImg = 'http://www.trueplookpanya.com/new/assets/images/icon/knowledge/menu_knowledge_0100.png';
?>
<section ng-app="app">
    <div class="container" ng-controller="searchCtrl as sc">
        <div class="row">
            <div class="col-md-8">
                <h2 class="h2">ความรู้และกิจกรรมเสริมทักษะ</h2>
                <div style="background-image: url('/assets/img-mocup/bg-knowledge.png');background-position: top center;background-size: cover;background-repeat: no-repeat;padding-bottom: 20px;" class="col-md-12">
                    <form method="post" ng-submit="sc.submitV2()">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 visible-xs visible-sm"><br></div>
                            <div class="col-md-6">
                                <label>หมวดหมู่ <img src="/assets/img-ex-ku/ajax-loader.gif" ng-init="<?= $this->input->get('category_id') ? 'data.category_id =' . $this->input->get('category_id') : '' ?>"  ng-show="list.level ? false : true;"></label>
                                <select required ng-change="select_level_data(data.category_id)" class="form-control sp-select-red" ng-model="data.category_id" ng-options="l.category_id as l.category_name for l in category_list">
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>ระดับชั้น <img src="/assets/img-ex-ku/ajax-loader.gif" ng-init="<?= $this->input->get('level_id') ? 'data.level_id=' . $this->input->get('level_id') : '' ?>" ng-show="list.level ? false : true;"></label>
                                <select required class="form-control sp-select-red" ng-model="data.level_id" ng-options="l.level_id as l.level_name for l in level_list"></select>
                                
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-md-6" style="padding-top: 5px">
                                <label>คำค้น</label>
                                <input type="text" ng-model="data.q" class="form-control sp-border-form-red">
                            </div>
                            <div class="col-md-6 text-right" style="padding-top: 30px">
                                <button type="submit" class="btn sp-button-red">ค้นหา</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div style="padding-top: 20px;padding-bottom: 20px;" class="col-md-12">
                    <nav class="pills-nav" id="variety-nav">
                        <?php if ($list_all OR $list_q) { ?>
                            <a class="active" title="ทั้งหมด" ng-click="sc.setPage(5)" data-type="media-portfolio">ทั้งหมด</a>
                        <?php } else { ?>
                            <?php if ($not_data) { ?>
                            <?php } else { ?>
                                <a ng-show="(all_tab==true?false:true)" class="<?= $_GET['page'] == 'media-child' || !isset($_GET['page']) ? 'active' : '' ?>" title="ความรู้ปฐมวัย" ng-click="sc.setPage(1)" data-type="media-child">ความรู้ปฐมวัย</a>
                                <a ng-show="(all_tab==true?false:true)" class="<?= $_GET['page'] == 'media-news' ? 'active' : '' ?>" title="สื่อน่ารู้" ng-click="sc.setPage(2)" data-type="media-news">สื่อน่ารู้</a>
                                <a ng-show="(all_tab==true?false:true)" class="<?= $_GET['page'] == 'media-prototype' ? 'active' : '' ?>" title="กิจกรรมนอกห้องเรียน" ng-click="sc.setPage(3)" data-type="media-prototype">กิจกรรมนอกห้องเรียน</a>
                                <a ng-show="(all_tab==true?false:true)" class="<?= $_GET['page'] == 'media-portfolio' ? 'active' : '' ?>" title="ลูกเสือ-เนตรนารี" ng-click="sc.setPage(4)" data-type="media-portfolio">ลูกเสือ-เนตรนารี</a>

                            <?php } ?>
                        <?php } ?>
                    </nav>
                </div>

                <?php if ($list_all) { ?>

                    <div class="col-md-12 text-center" id="p5" ng-show="<?= $list_all == true ? TRUE : FALSE ?>">
                        <div class="row" ng-if="(list.<?= $use_api ?>.length > 0 ? false : true)">
                            <div class="col-lg-12" ng-if="notfound<?= $use_loadmore ?>" style="padding: 10px; color: rgb(180, 179, 179);">ไม่พบข้อมูลที่คุณค้นหา<br></div>
                        </div>
                        <div class="row" ng-style="(loadOpa ? {'opacity': 0.5} : {})">
                            <?php if (isset($list_data)) { ?>
                                <?php foreach ($list_data as $key => $value) { ?>
                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 list-data-<?= $use_loadmore ?> list-from-server">
                                        <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id . '-' . $value->mul_source_id ?>" title="<?= $value->mul_content_subject ?>" class="grid-thumbnail css-crop-150" target="_blank">
                                            <img src="<?= $value->thumb ?>" alt="<?= $value->mul_content_subject ?>">
                                        </a>
                                        <figcaption class="grid-thumbnail--caption">
                                            <p class="text-limit-2">
                                                <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id . '-' . $value->mul_source_id ?>" title="<?= $value->mul_content_subject ?>" target="_blank">
                                                    <?= $value->mul_content_subject ?>
                                                </a>
                                            </p>
                                        </figcaption>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 list-data-<?= $use_loadmore ?>" ng-repeat="l in list.<?= $use_api ?>">
                                <a ng-href="{{('http://www.trueplookpanya.com/knowledge/detail/'+l.mul_content_id+'-'+l.mul_source_id)}}" title="{{(l.mul_content_subject + '')}}" class="grid-thumbnail css-crop-150" target="_blank">
                                    <img ng-src="{{(l.thumb + '')}}" alt="{{(l.mul_content_subject + '')}}">
                                </a>
                                <figcaption class="grid-thumbnail--caption">
                                    <p class="text-limit-2">
                                        <a class="css-text-limit-two" ng-href="{{'http://www.trueplookpanya.com/knowledge/detail/'+l.mul_content_id+'-'+l.mul_source_id}}" href="" title="{{(l.mul_content_subject + '')}}" target="_blank" ng-bind="l.mul_content_subject">
                                        </a>
                                    </p>
                                </figcaption>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 pt-5 text-center">
                                <img src="/assets/img-ex-ku/ajax-loader.gif" ng-show="loadmore">
                                <button ng-hide="loadmore || loader<?= $use_loadmore ?>" style="padding-top: 1px;padding-bottom: 1px;width: 100%;border-radius: 16px;border: none;background-color: #E02125;color: white;height: 30px;font-size: 23px;" ng-click="sc.getOffset<?= $_GET['category_id'] == 10000 ? 'child' : '' ?>(<?= $_GET['category_id'] == 10000 ? '' : $_GET['category_id'] . ',' ?> 'list-data-<?= $use_loadmore ?>', list.<?= $use_api ?>)">โหลดเพิ่มเติม</button>
                            </div>
                        </div>
                    </div>
                <?php } else if ($list_q) { ?>
                    <div class="col-md-12 text-center" id="p5" ng-show="<?= $list_q == TRUE ? TRUE : FALSE ?>">
    <!--                        <div class="row" ng-if="(list.<?= $use_api ?>.length > 0 ? false : true)">
                        <div class="col-lg-12" ng-if="notfound<?= $use_loadmore ?>" style="padding: 10px; color: rgb(180, 179, 179);">ไม่พบข้อมูลที่คุณค้นหา<br></div>
                    </div>-->
                        <div class="row" ng-style="(loadOpa ? {'opacity': 0.5} : {})">

                            <?php if (isset($list_data)) { ?>
                                <?php foreach ($list_data as $key => $value) { ?>
                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 list-data-<?= $use_loadmore ?> list-from-server">
                                        <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->content_id . '-' . $value->content_id_child ?>" title="<?= $value->content_subject ?>" class="grid-thumbnail css-crop-150" target="_blank">
                                            <img src="<?= $value->thumbnail ?>" alt="<?= $value->content_subject ?>">
                                        </a>
                                        <figcaption class="grid-thumbnail--caption">
                                            <p class="text-limit-2">
                                                <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->content_id . '-' . $value->content_id_child ?>" title="<?= $value->content_subject ?>" target="_blank">
                                                    <?= $value->content_subject ?>
                                                </a>
                                            </p>
                                        </figcaption>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 list-data-<?= $use_loadmore ?>" ng-repeat="l in list.<?= $use_api ?>">
                                <a ng-href="{{('http://www.trueplookpanya.com/knowledge/detail/'+l.content_id+'-'+l.content_id_child)}}" title="{{(l.content_subject + '')}}" class="grid-thumbnail css-crop-150" target="_blank">
                                    <img ng-src="{{(l.thumbnail + '')}}" alt="{{(l.content_subject + '')}}">
                                </a>
                                <figcaption class="grid-thumbnail--caption">
                                    <p class="text-limit-2">
                                        <a class="css-text-limit-two" ng-href="{{'http://www.trueplookpanya.com/knowledge/detail/'+l.content_id+'-'+l.content_id_child}}" href="" title="{{(l.content_subject + '')}}" target="_blank" ng-bind="l.content_subject">
                                        </a>
                                    </p>
                                </figcaption>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 pt-5 text-center">
                                <img src="/assets/img-ex-ku/ajax-loader.gif" ng-show="loadmore">
                                <button ng-hide="loadmore || loader<?= $use_loadmore ?>" style="padding-top: 1px;padding-bottom: 1px;width: 100%;border-radius: 16px;border: none;background-color: #E02125;color: white;height: 30px;font-size: 23px;" ng-click="sc.getOffsetAll('list-data-<?= $use_loadmore ?>', list.<?= $use_api ?>)">โหลดเพิ่มเติม</button>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <?php if ($not_data) { ?>
                        <div class="row" >
                            <div class="col-lg-12"style="padding: 10px; color: rgb(180, 179, 179);text-align: center;">ไม่พบข้อมูลที่คุณค้นหา<br></div>
                        </div>
                    <?php } else { ?>

                        <div class="col-md-12 text-center" id="p4" ng-show="sc.isPage(1)">

                            <div class="row" ng-if="(list.activi_child.length > 0 ? false : true)">
                                <div class="col-lg-12" ng-if="notfound4" style="padding: 10px; color: rgb(180, 179, 179);">ไม่พบข้อมูลที่คุณค้นหา<br></div>
                            </div>
                            <div class="row" ng-style="(loadOpa ? {'opacity': 0.5} : {})">
                                <?php
                                if (isset($list_child)) {
                                    foreach ($list_child as $key => $value) {
                                        ?>
                                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 list-data-4 list-from-server">
                                            <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id . '-' . $value->mul_source_id ?>" title="<?= $value->mul_content_subject ?>" class="grid-thumbnail css-crop-150" target="_blank">
                                                <img src="<?= $value->thumb ?>" alt="<?= $value->mul_content_subject ?>">
                                            </a>
                                            <figcaption class="grid-thumbnail--caption">
                                                <p class="text-limit-2">
                                                    <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id . '-' . $value->mul_source_id ?>" title="<?= $value->mul_content_subject ?>" target="_blank">
                                                        <?= $value->mul_content_subject ?>
                                                    </a>
                                                </p>
                                            </figcaption>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 list-data-4" ng-repeat="l in list.activi_child">
                                    <a ng-href="{{('http://www.trueplookpanya.com/knowledge/detail/'+l.mul_content_id+'-'+l.mul_source_id)}}" title="{{(l.mul_content_subject + '')}}" class="grid-thumbnail css-crop-150" target="_blank">
                                        <img ng-src="{{(l.thumb + '')}}" alt="{{(l.mul_content_subject + '')}}">
                                    </a>
                                    <figcaption class="grid-thumbnail--caption">
                                        <p class="text-limit-2">
                                            <a class="css-text-limit-two" ng-href="{{'http://www.trueplookpanya.com/knowledge/detail/'+l.mul_content_id+'-'+l.mul_source_id}}" href="" title="{{(l.mul_content_subject + '')}}" target="_blank" ng-bind="l.mul_content_subject">
                                            </a>
                                        </p>
                                    </figcaption>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 pt-5 text-center">
                                    <img src="/assets/img-ex-ku/ajax-loader.gif" ng-show="loadmore">
                                    <button ng-hide="loadmore || loader4" style="padding-top: 1px;padding-bottom: 1px;width: 100%;border-radius: 16px;border: none;background-color: #E02125;color: white;height: 30px;font-size: 23px;" ng-click="sc.getOffsetchild('list-data-4', list.activi_child)">โหลดเพิ่มเติม</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center" id="p2" ng-show="sc.isPage(2)">

                            <div class="row" ng-if="(list.activi_9200.length > 0 ? false : true)">
                                <div class="col-lg-12" ng-if="notfound2" style="padding: 10px; color: rgb(180, 179, 179);">ไม่พบข้อมูลที่คุณค้นหา<br></div>
                            </div>

                            <div class="row" ng-style="(loadOpa ? {'opacity': 0.5} : {})">
                                <?php
                                if (isset($list_9200)) {
                                    foreach ($list_9200 as $key => $value) {
                                        ?>
                                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 list-data-2 list-from-server padding">
                                            <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id . '-' . $value->mul_source_id ?>" title="<?= $value->mul_content_subject ?>" class="grid-thumbnail css-crop-150" target="_blank">
                                                <img src="<?= $value->thumb ?>" alt="<?= $value->mul_content_subject ?>">
                                            </a>
                                            <figcaption class="grid-thumbnail--caption">
                                                <p class="text-limit-2">
                                                    <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id . '-' . $value->mul_source_id ?>" title="<?= $value->mul_content_subject ?>" target="_blank">
                                                        <?= $value->mul_content_subject ?>
                                                    </a>
                                                </p>
                                            </figcaption>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 list-data-2 padding" ng-repeat="l in list.activi_9200">
                                    <a ng-href="{{'http://www.trueplookpanya.com/knowledge/detail/'+l.mul_content_id+'-'+l.mul_source_id}}" title="{{(l.mul_content_subject + '')}}" class="grid-thumbnail css-crop-150" target="_blank">
                                        <img ng-src="{{(l.thumb + '')}}" alt="{{(l.mul_content_subject + '')}}">
                                    </a>
                                    <figcaption class="grid-thumbnail--caption">
                                        <p class="text-limit-2">
                                            <a
                                                ng-href="{{'http://www.trueplookpanya.com/knowledge/detail/'+l.mul_content_id+'-'+l.mul_source_id}}"
                                                href="" title="{{(l.mul_content_subject + '')}}"
                                                target="_blank"
                                                ng-bind="l.mul_content_subject">
                                            </a>
                                        </p>
                                    </figcaption>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 pt-5 text-center">
                                    <img src="/assets/img-ex-ku/ajax-loader.gif" ng-show="loadmore">
                                    <button ng-hide="loadmore || loader2" style="padding-top: 1px;padding-bottom: 1px;width: 100%;border-radius: 16px;border: none;background-color: #E02125;color: white;height: 30px;font-size: 23px;" ng-click="sc.getOffset(9200, 'list-data-2', list.activi_9200)">โหลดเพิ่มเติม</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center" id="p3" ng-show="sc.isPage(3)">
        <!--                    <img src="/assets/img-ex-ku/ajax-loader.gif" ng-show="loader2">-->
                            <div class="row" ng-if="(list.activi_9300.length > 0 ? false : true)">
                                <div class="col-lg-12" ng-if="notfound3" style="padding: 10px; color: rgb(180, 179, 179);">ไม่พบข้อมูลที่คุณค้นหา<br></div>
                            </div>
                            <div class="row" ng-style="(loadOpa ? {'opacity': 0.5} : {})">
                                <?php
                                if (isset($list_9300)) {
                                    foreach ($list_9300 as $key => $value) {
                                        ?>
                                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 list-data-3 list-from-server">
                                            <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id . '-' . $value->mul_source_id ?>" title="<?= $value->mul_content_subject ?>" class="grid-thumbnail css-crop-150" target="_blank">
                                                <img src="<?= $value->thumb ?>" alt="<?= $value->mul_content_subject ?>">
                                            </a>
                                            <figcaption class="grid-thumbnail--caption">
                                                <p class="text-limit-2">
                                                    <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id . '-' . $value->mul_source_id ?>" title="<?= $value->mul_content_subject ?>" target="_blank">
                                                        <?= $value->mul_content_subject ?>
                                                    </a>
                                                </p>
                                            </figcaption>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 list-data-3" ng-repeat="l in list.activi_9300">
                                    <a ng-href="{{('http://www.trueplookpanya.com/knowledge/detail/'+l.mul_content_id+'-'+l.mul_source_id)}}" title="{{(l.mul_content_subject + '')}}" class="grid-thumbnail css-crop-150" target="_blank">
                                        <img ng-src="{{(l.thumb + '')}}" alt="{{(l.mul_content_subject + '')}}">
                                    </a>
                                    <figcaption class="grid-thumbnail--caption">
                                        <p class="text-limit-2">
                                            <a class="css-text-limit-two" ng-href="{{'http://www.trueplookpanya.com/knowledge/detail/'+l.mul_content_id+'-'+l.mul_source_id}}" href="" title="{{(l.mul_content_subject + '')}}" target="_blank" ng-bind="l.mul_content_subject">
                                            </a>
                                        </p>
                                    </figcaption>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 pt-5 text-center">
                                    <img src="/assets/img-ex-ku/ajax-loader.gif" ng-show="loadmore">

                                    <button ng-hide="loadmore || loader3" style="padding-top: 1px;padding-bottom: 1px;width: 100%;border-radius: 16px;border: none;background-color: #E02125;color: white;height: 30px;font-size: 23px;" ng-click="sc.getOffset(9300, 'list-data-3', list.activi_9300)">โหลดเพิ่มเติม</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center" id="p1" ng-show="sc.isPage(4)">
        <!--                    <img src="/assets/img-ex-ku/ajax-loader.gif" ng-show="loader3">-->
                            <div class="row" ng-if="(list.activi_9100.length > 0 ? false : true)">
                                <div class="col-lg-12" ng-if="notfound1" style="padding: 10px; color: rgb(180, 179, 179);">ไม่พบข้อมูลที่คุณค้นหา<br></div>
                            </div>
                            <div class="row" ng-style="(loadOpa ? {'opacity': 0.5} : {})">
                                <?php
                                if (isset($list_9100)) {
                                    foreach ($list_9100 as $key => $value) {
                                        ?>
                                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 list-data-1 list-from-server">
                                            <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id . '-' . $value->mul_source_id ?>" title="<?= $value->mul_content_subject ?>" class="grid-thumbnail css-crop-150" target="_blank">
                                                <img src="<?= $value->thumb ?>" alt="<?= $value->mul_content_subject ?>">
                                            </a>
                                            <figcaption class="grid-thumbnail--caption">
                                                <p class="text-limit-2">
                                                    <a href="http://www.trueplookpanya.com/knowledge/detail/<?= $value->mul_content_id . '-' . $value->mul_source_id ?>" title="<?= $value->mul_content_subject ?>" target="_blank">
                                                        <?= $value->mul_content_subject ?>
                                                    </a>
                                                </p>
                                            </figcaption>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 list-data-1" ng-repeat="l in list.activi_9100">
                                    <a ng-href="{{('http://www.trueplookpanya.com/knowledge/detail/'+l.mul_content_id+'-'+l.mul_source_id)}}" title="{{(l.mul_content_subject + '')}}" class="grid-thumbnail css-crop-150" target="_blank">
                                        <img ng-src="{{(l.thumb + '')}}" alt="{{(l.mul_content_subject + '')}}">
                                    </a>
                                    <figcaption class="grid-thumbnail--caption">
                                        <p class="text-limit-2">
                                            <a class="css-text-limit-two" ng-href="{{'http://www.trueplookpanya.com/knowledge/detail/'+l.mul_content_id+'-'+l.mul_source_id}}" href="" title="{{(l.mul_content_subject + '')}}" target="_blank" ng-bind="l.mul_content_subject">
                                            </a>
                                        </p>
                                    </figcaption>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 pt-5 text-center">
                                    <img src="/assets/img-ex-ku/ajax-loader.gif" ng-show="loadmore">
                                    <button ng-hide="loadmore || loader1" style="padding-top: 1px;padding-bottom: 1px;width: 100%;border-radius: 16px;border: none;background-color: #E02125;color: white;height: 30px;font-size: 23px;" ng-click="sc.getOffset(9100, 'list-data-1', list.activi_9100)">โหลดเพิ่มเติม</button>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                <?php } ?>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <h2 class="h2">Theme แนะนำ</h2>
                    <div class="col-lg-12 text-center">
                        <img src="/assets/img-ex-ku/ajax-loader.gif" ng-show="list.banner ? false : true;">
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-center" ng-repeat="l in list.banner">
                            <a title="{{('' + l.title)}}" target="_blank" ng-href="{{'' + l.link}}">
                                <img title="{{('' + l.title)}}" alt="{{l.title| limitTo:20}}" style="width: 128px; height: 96px; border: 0px solid #cccccc; padding: 1px;border-radius: 5px" ng-src="{{('' + l.thumbnail)}}">
                                <p class="text-limit-2" ng-bind="l.title"></p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h2 class="h2">เพลงเพื่อการศึกษา</h2>
                    <div class="col-lg-12 text-center">
                        <img src="/assets/img-ex-ku/ajax-loader.gif" ng-show="list.edusong ? false : true;">
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 pr-0" id="webboard">
                        <figure ng-repeat="l in list.edusong" class="exam-list--item" style="margin-top:-8px;border-bottom: 1px solid #E6E0E0;padding-bottom: 5px;">
                            <a target="_blank" ng-href="{{'http://www.trueplookpanya.com/true/activity_detail.php?cms_id='+l.cms_id}}" title="Thumbnail" class="exam-list--thumb">
                                <i class="icon-document"></i>
                            </a>
                            <figcaption class="exam-list--body">
                                <a  target="_blank" ng-href="{{'http://www.trueplookpanya.com/true/activity_detail.php?cms_id='+l.cms_id}}" title="{{l.cms_subject}}">
                                    <div class="css-text-limit-two" style="height: 23px;" ng-bind="l.cms_subject"></div>
                                    <div style="margin-top: -2px;font-size: 0.7em">ผลิตโดย : <span style="color:#28cbf7">true click life</span></div>
                                </a>
                            </figcaption>
                        </figure>
                    </div>
                    <div class="col-lg-12 text-right">
                        <a style="padding:5px;border-radius: 5px;border: none;background-color: #E02125;color: white" href="http://www.trueplookpanya.com/true/activity_list.php?cms_category_id=67" target="_blank">ดูทั้งหมด</a>
                    </div>
                </div>
                <div class="row">
                    <h2 class="h2">ภาพถ่ายเพื่อการศึกษา</h2>
                    <div class="col-lg-12 text-center">
                        <img src="/assets/img-ex-ku/ajax-loader.gif" ng-show="list.eduimg ? false : true;">
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-center" ng-repeat="l in list.eduimg">
                            <a title="{{l.edu_photo_name}}" target="_blank" ng-href="{{'http://www.trueplookpanya.com/true/photoAlbum_detail.php?id='+l.edu_photo_id}}">
                                <img title="{{l.edu_photo_file[0].edu_photo_files_shrtdesc}}" alt="{{l.edu_photo_file[0].edu_photo_files_shrtdesc| limitTo:20}}" style="width: 128px; height: 96px; border: 0px solid #cccccc; padding: 1px;" ng-src="{{'/data/product/media/' + l.edu_photo_file[0].photo_path + '/' + l.edu_photo_file[0].edu_photo_files_name}}">
                                <p class="text-limit-2" ng-bind="l.edu_photo_name"></p>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-12 text-right">
                        <a style="padding:5px;border-radius: 5px;border: none;background-color: #E02125;color: white" href="http://www.trueplookpanya.com/true/photoAlbum.php" target="_blank">ดูทั้งหมด</a>
                    </div>
                </div>
                <div class="row">
                    <h2 class="h2">infographic</h2>
                    <div class="col-lg-12 text-center">
                        <img src="/assets/img-ex-ku/ajax-loader.gif" ng-show="list.infogr ? false : true;">
                    </div>
                    <div class="row">
                        <div class="col-md-12" ng-repeat="l in list.infogr">
                            <a ng-href="{{l.link + ''}}" target="_blank">
                                <img class="img-responsive" ng-src="{{'' + l.image + ''}}">
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-12 text-right">
                        <br>
                        <a style="padding:5px;border-radius: 5px;border: none;background-color: #E02125;color: white" href="http://www.trueplookpanya.com/infographic" target="_blank">ดูทั้งหมด</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
