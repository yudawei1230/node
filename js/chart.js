var ctrl = 'Chart';
var OK = 0;
var begin = 0;
var yearsbegin = 0;
var detail = 0;
var seriesName = ["本月计划", "本月累计", "上月累计", "完成率（%）"];
var seriesYearName = ["本月计划", "本月累计", "完成率（%）", "同比计划", "同比累计", "同比完成率（%）"];
var myModule = angular.module("myapp", []);
var touchEnd;
//JqueryInit && JqueryModule
jQuery(document).ready(function () {
    var socket=io.connect(),//与服务器进行连接
    button=document.getElementById('selectDate');
    button.onclick=function(){
        socket.emit('foo', 'hello');//发送一个名为foo的事件，并且传递一个字符串数据‘hello’
    }
    socket.on('news',function(data){
        console.log(data);
    });
    jQuery('.wait').css("display", 'none');
    jQuery('.wait').css("top", window.innerHeight / 2 - 16 + "px");
    jQuery('.wait').css("left", window.innerWidth / 2 - 16 + "px");
    jQuery('#lastMon').css("display", 'none');
    jQuery('#lastDay').css("display", 'initial');
    jQuery('#nextMon').css("display", 'none');
    jQuery('#nextDay').css("display", 'initial');
    jQuery('#selectDate').click(function () {
        //swiper换页属性事件
        var mySwiper = new Swiper('.swiper-container', {
            direction: 'horizontal',
            height: window.innerHeight,
            width: window.innerWidth,

            // 如果需要分页器
            pagination: '.swiper-pagination',
            onSlideNextStart: function (swiper) {
                if (swiper.activeIndex == 1)
                    swiper1();
                if (swiper.activeIndex == 2)
                    swiper2();
            },
            onSlidePrevStart: function (swiper) {
                if (swiper.activeIndex == 0)
                    swiper0();
                else if (swiper.activeIndex == 1)
                    swiper1();
            }, onInit: function (swiper) {
                touchEnd = setTimeout(function () {
                    jQuery('a').addClass('btnTransition');
                }, 1000);
            }, onTouchStart: function (swiper, even) {
                if (touchEnd)
                    clearTimeout(touchEnd);
                jQuery('a').css("opacity", "0.7");
                jQuery('a').removeClass("btnTransition");
            },
            onTouchEnd: function (swiper, even) {
                if (touchEnd)
                    clearTimeout(touchEnd);
                touchEnd = setTimeout(function () {
                    jQuery('a').addClass('btnTransition');
                }, 1000);
            },
            onLazyImageReady: function (swiper) {
                alert('延迟加载图片完成');
                console.log(swiper);//Swiper实例
                console.log(slide);//哪个slide里面的图片在加载
                console.log(image);//哪个图片在加载
            }
        });
    });
});
//JqueryFn
function swiper0() {
    showMonthChart("container", addData(seriesName, []), [], '月累计产品产量（环比）');
    window.scrollTo(0, 0);
    $('#lastMon').css("display", 'none');
    $('#lastDay').css("display", 'initial');
    $('#nextMon').css("display", 'none');
    $('#nextDay').css("display", 'initial');
}
function swiper1() {
    if (angular.element($('#productDetail_select')).scope().productSelect == "请选择产品明细" || angular.element($('#productDetail_select')).scope().productSelect == "返回各类产量") {
        showMonthChart("container", addData(seriesName, begin), begin[0], '月累计产品产量（环比）');
    }
    else {
        showMonthChart("container", detail[0], detail[1], '月累计药品用量');
    }
    window.scrollTo(0, 0);
    $('#lastMon').css("display", 'initial');
    $('#lastDay').css("display", 'none');
    $('#nextMon').css("display", 'initial');
    $('#nextDay').css("display", 'none');
}
function swiper2() {
    showMonthChart("yearsComparation",addData(seriesYearName, yearsbegin), yearsbegin[0], '月累计产品产量（同比）');
    window.scrollTo(0, 0);
}
function DataNameChange(data) {
    while (data.indexOf("干粉一") != -1 || data.indexOf("干粉三") != -1) {
        data = data.replace(/干粉一/, "干粉");
        data = data.replace(/干粉三/, "干粉");
    }
    data = data.split("&");
    for (i in data) {
        data[i] = eval(data[i].split("'")[1]);
    }
    return data;
}
//月报表数据结构转换
function changeYearData(data) {
    data = DataNameChange(data);
    var thisYearData = [data[0], data[1], data[2]];
    var lastyeatData = [data[0], data[3], data[4]];
    thisYearData = changeData(thisYearData);
    lastyeatData = changeData(lastyeatData);
    for (i in lastyeatData)
    {
        if (i == 0)
            continue;
        thisYearData.push(lastyeatData[i]);
    }
    return thisYearData;
}
function changeData(data) {
    if (typeof (data) == 'string')
        data = DataNameChange(data);
    var k = data[0].length;
    var is = 0;
    data[0][k] = [];
    //产品    
    for (i in data[0]) {
        is = 0;
        for (j in data[0][k]) {
            if (data[0][k][j] == data[0][i].CategoryID) {
                is = 1;
            }
        }
        if (is == 0)
            data[0][k].push(data[0][i].CategoryID);
    }
    data[0][k].pop();
    for (j in data) {
        if (j == 0)
            continue;
        if (data[j] == 0) {
            data[j] = [];
            for (i = 0 ; i < data[0][k].length; i++) {
                data[j].push(0);
            }
        }
        else if (j == 1) {
            var m = data[1].length;
            data[1][m] = [];
        }
        else if (j == 2) {
            var n = data[2].length;
            data[2][n] = [];
        }
        else if (j == 3) {
            var o = data[3].length;
            data[3][o] = [];
        }
    }

    //产品月计划、本月累计、上月累计计算
    for (i in data[0][k]) {
        if (m)
            data[1][m][i] = 0;
        if (n)
            data[2][n][i] = 0;
        if (o)
            data[3][o][i] = 0;
        for (j in data[0]) {
            if (data[0][j].CategoryID == data[0][k][i]) {
                if (m)
                    for (a in data[1]) {
                        if (data[1][a].ProductID == data[0][j].ProductID) {
                            data[1][m][i] += eval(data[1][a].ProductDayPlan);
                        }
                    }
                if (n)
                    for (b in data[2]) {
                        if (data[2][b].ProductID == data[0][j].ProductID) {
                            data[2][n][i] += eval(data[2][b].ProductOutput);
                        }
                    }
                if (o)
                    for (c in data[3]) {
                        if (data[3][c].ProductID == data[0][j].ProductID) {
                            data[3][o][i] += eval(data[3][c].ProductOutput);
                        }
                    }

            }
            if (j == k)
                break;
        }
    }
    data[0] = data[0][k];
        if (m)
            data[1] = data[1][m];
        if (n)
            data[2] = data[2][n];
        if (o)
            data[3] = data[3][o];
            data[data.length] = [];
            for (i in data[2]) {
                if (data[1][i] == 0) {
                    data[data.length-1].push(0);
                    continue;
                }
                data[data.length-1].push(data[2][i] / data[1][i] * 100);
            }
    for (i in data) {
        if (i == 0)
            continue;
        for (j in data[i]) {
            data[i][j] = Math.round(data[i][j] * 100) / 100;
        }
    }
    return data;
}
//图表数据导入显示
function showMonthChart(id, seriesdata, x,title) {
    $('#' + id).highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: '茂名高科'
        },
        subtitle: {
            text: angular.element($("#years")).scope().year+'年'+angular.element($("#months")).scope().month + title
        },
        xAxis: {
            categories: x,
        },
        yAxis: {
            min: 0,
            title: {
                text: '产量 (t)'
            }
        },
        credits: {
            enabled: false
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true, // dataLabels设为true
                    style: {
                        color: '#D7DEE9'
                    }
                }
            }
        },
        series: seriesdata,
        dataLabels: {
            enabled: true,
            rotation: -90,
            color: '#FFFFFF',
            align: 'right',
            x: 4,
            y: 10,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif',
                textShadow: '0 0 3px black'
            }
        }
    });
}
//highchart数据导入结构转换
function addData(seriesName, data) {
    var CData = [];
    var JsonData = {}
    for (i in data) {
        if (i == 0)
            continue;
        JsonData = {};
        JsonData["data"] = data[i];
        JsonData["name"] = seriesName[i - 1];
        CData.push(JsonData);
    }
    return CData;
}
//
//
//angular方法
myModule.factory("chartFn", function () {
    function addData(seriesName, data) {
        var CData = [];
        var JsonData = {}
        for (i in data) {
            if (i == 0)
                continue;
            JsonData = {};
            JsonData["data"] = data[i];
            JsonData["name"] = seriesName[i - 1];
            CData.push(JsonData);
        }
        return CData;
    }
    //数据类型转换
    function ProductDataInit(data) {
        for (i in data) {
            data[i] = ProductData(data[i]);
        }
        return data;
    }
    //数据类型转换
    function ChangeType(data) {
        data = data.split("$");
        data.pop();
        for (h in data) {
            data[h] = data[h].split("|");
            data[h].pop();
            for (i in data[h]) {
                data[h][i] = data[h][i].split("&");
                data[h][i].pop();
                for (j in data[h][i]) {
                    data[h][i][j] = eval(data[h][i][j].split("'")[1]);
                }
            }
        }
        return data
    }
    function detailSettle(data, name) {
        data.pop();
        var newData = [];
        var dataName = [];
        var is = false;
        for (i in data) {
            for (j in data[i]) {
                for (k in newData) {
                    if (data[i][j].YaopinID == newData[k].name)
                        is = true;
                }
                if (is == false)
                    newData.push({ name: data[i][j].YaopinID, data: [] });
                is = false;
            }
        }
        for (o in newData) {
            for (i in data) {
                if (o == 0)
                    dataName.push(data[i][0].ProductID);
                newData[o].data[i] = 0;
                for (j in data[i]) {
                    if (data[i][j].YaopinID == newData[o].name) {
                        newData[o].data[i] += eval(data[i][j].YaopinUse);
                        newData[o].data[i] = Math.round(newData[o].data[i] * 100) / 100;
                    }
                }
            }
        }
        var allData = [newData, dataName];
        return allData;
    }
    //数据结构转换
    function ProductData(data) {
/*    var Jsondata = {};
    Jsondata = dataInit(Jsondata);
    Jsondata['ProductID'] = data[0][0].ProductID;
    Jsondata['CategoryID'] = data[0][0].CategoryID;
    if (data[0][0].ProductDayPlan)
        Jsondata['PlanYield'] = eval(data[0][0].ProductDayPlan);
    else
        Jsondata['PlanYield'] = 0;
    if (data.length == 1)
        return Jsondata;
    if (data[0][0].ProductDayPlan != "")
        Jsondata['PlanYield'] = eval(data[0][0].ProductDayPlan);
    for (i in data) {
        for (j in data[i]) {
            if (data[i][j].ProductOutput) {
                switch (data[i][j].BanciID) {
                    case "零班": data[i][j].ProductOutput = eval(data[i][j].ProductOutput); Jsondata["L" + "RealYield"] = data[i][j].ProductOutput; Jsondata["L" + "Beizhu"] = data[i][j].ProductRemark; break;
                    case "白班": data[i][j].ProductOutput = eval(data[i][j].ProductOutput); Jsondata["B" + "RealYield"] = data[i][j].ProductOutput; Jsondata["B" + "Beizhu"] = data[i][j].ProductRemark; break;
                    case "中班": data[i][j].ProductOutput = eval(data[i][j].ProductOutput); Jsondata["Z" + "RealYield"] = data[i][j].ProductOutput; Jsondata["Z" + "Beizhu"] = data[i][j].ProductRemark; break;
                    default: break;
                }
            }
            else {
                switch (data[i][j].YaopinID) {
                    case "硫酸": data[i][j].YaopinID = "LS"; break;
                    case "保险粉": data[i][j].YaopinID = "BXF"; break;
                    case "六偏": data[i][j].YaopinID = "LP"; break;
                    case "液碱": data[i][j].YaopinID = "YJ"; break;

                }
                switch (data[i][j].BanciID) {
                    case "零班": Jsondata["L" + data[i][j].YaopinID] = eval(data[i][j].YaopinUse); break;
                    case "白班": Jsondata["B" + data[i][j].YaopinID] = eval(data[i][j].YaopinUse); break;
                    case "中班": Jsondata["Z" + data[i][j].YaopinID] = eval(data[i][j].YaopinUse); break;
                    default: break;
                }
            }

        }

    }
    return Jsondata;*/
}
    //JSON元素名转换
    function dataInit(data) {
    var banci = ["L", "B", "Z"];
    for (i in banci) {
        data[banci[i] + "RealYield"] = 0;
        data[banci[i] + "LS"] = 0;
        data[banci[i] + "BXF"] = 0;
        data[banci[i] + "LP"] = 0;
        data[banci[i] + "DC"] = 0;
        data[banci[i] + "YJ"] = 0;
    }
    return data;
}

    return {
        'addData': addData,
        'ProductDataInit': ProductDataInit,
        'ChangeType': ChangeType,
        'detailSettle': detailSettle
    };
});
//angular
myModule.controller(ctrl, ["$scope", "$http", "chartFn", function ($scope, $http, chartFn) {
    //页面初始化，表格隐藏
    $scope.form = true;
    //初始化年月日
    $scope.years = [];
    $scope.months = [];
    $scope.days = [];
    $scope.productDetails = [{ productDetails: "干粉" }, { productDetails: "瓷土" }, { productDetails: "无漂土" }];
    var year = new Date();
    for (var i = new Date().getFullYear() - 10, j = 0; i <= new Date().getFullYear() ; i++, j++) {
        $scope.years[j] = {};
        $scope.years[j]['years'] = i;
    }
    for (var i = 1, j = 0 ; i <= 12; i++, j++) {
        $scope.months[j] = {};
        $scope.months[j]['months'] = i;
    }
    for (var i = 1, j = 0; i <= 31 ; i++, j++) {
        $scope.days[j] = {};
        $scope.days[j]['days'] = i;
    }
    //默认年月日
    $scope.year = 2015 //new Date().getFullYear();
    $scope.month = 10 //new Date().getMonth() + 1;
    $scope.day = 29 //new Date().getDate() - 1;
    $scope.yearSelect = 2015//new Date().getFullYear();
    $scope.monthSelect = 10//new Date().getMonth() + 1;
    $scope.daySelect = 29//new Date().getDate() - 1;
    $scope.back = function () {
        window.location.reload();
    }
    $scope.tomorrow = function () {
        if ($scope.day == new Date($scope.year, $scope.month, 0).getDate())
        {
            if ($scope.month != 12)
            {
                $scope.month++;
                $scope.day = 1;
            }
            else
            {
                $scope.month = 1;
                $scope.year++;
                $scope.day = 1;
            }
            OK=0;
        }
        else
            $scope.day++;
        $scope.show();
        window.scrollTo(0, 0);
    }
    $scope.yesterday = function () {
        if ($scope.day == 1)
        {
            if ($scope.month == 1) {
                $scope.year--;
                $scope.month = 12;
                $scope.day = new Date($scope.year, $scope.month, 0).getDate();
            }
            else {
                $scope.month--;
                $scope.day = new Date($scope.year, $scope.month, 0).getDate();
            }
            OK = 0;
        }
        else
            $scope.day--;
        $scope.show();
        window.scrollTo(0, 0);
    }
    $scope.nextMonth = function () {
        if ($scope.month != 12) {
            $scope.month++;
            $scope.day = 1;
        }
        else {
            $scope.month = 1;
            $scope.year++;
            $scope.day = 1;
        }
        OK = 0;
        $scope.show();
        window.scrollTo(0, 0);
    }
    $scope.lastMonth = function () {
        if ($scope.month != 1) {
            $scope.month--;
            $scope.day = 1;
        }
        else {
            $scope.month = 12;
            $scope.year--;
            $scope.day = 1;
        }
        OK = 0;
        $scope.show();
        window.scrollTo(0, 0);
    }
    //年月改变日期改变
    $scope.changeYear = function (year) {
        $scope.days = [];
        $scope.daySelect = new Date(year, $scope.month, 0).getDate();
        $scope.day = new Date(year, $scope.month, 0).getDate();
        for (var i = 1, j = 0; i <= new Date(year, $scope.month, 0).getDate() ; i++, j++) {
            $scope.days[j] = {};
            $scope.days[j]['days'] = i;
        }
    }
    $scope.changeMonth = function (month) {
        $scope.days = [];
        $scope.daySelect = new Date($scope.year, month, 0).getDate();
        $scope.day = new Date($scope.year, month, 0).getDate();
        for (var i = 1, j = 0; i <= new Date($scope.year, month, 0).getDate() ; i++, j++) {
            $scope.days[j] = {};
            $scope.days[j]['days'] = i;
        }
    }
    $scope.changeDay = function (day) {
        $scope.daySelect = day;
        $scope.day = day;
    }
    $scope.changeproductDetail = function (productName) {
        $scope.mywait = { 'display': 'initial' };
        $scope.productSelect = productName;
        if (productName != "返回各类产量")
            $http({ method: 'POST', url: '../createServer.js', params: { 'action': 'findDataDetail', 'MonthEnd': $scope.year + '-' + $scope.month + '-' + new Date($scope.year, $scope.month, 0).getDate(), 'monthStart': $scope.year + '-' + $scope.month + '-' + '1', 'product': productName } }).success(function (data) {
                if (!data)
                    alert("这个月无此类产品药品用量");
                data = DataNameChange(data);
                data = chartFn.detailSettle(data, productName);
                detail = data;
                $scope.productDetails = [{ productDetails: "干粉" }, { productDetails: "瓷土" }, { productDetails: "无漂土" }, { productDetails: "返回各类产量" }];
                showMonthChart("container", data[0], data[1], '月累计药品用量');
                $scope.mywait = { 'display': 'none' };
            });
        else {
            setTimeout(function () {
                $scope.$apply(function () {
                    //wrapped this within $apply
                    $scope.productDetails = [{ productDetails: "干粉" }, { productDetails: "瓷土" }, { productDetails: "无漂土" }];
                    $scope.productDetail = "";
                    showMonthChart("container", chartFn.addData(seriesName, begin), begin[0], '月累计产品产量（环比）');
                    $scope.mywait = { 'display': 'none' };
                });
            }, 100);
            
        }
    }
    $scope.show = function () {
        $scope.mywait = { 'display': 'initial' };
        $http({ method: 'POST', url: '../createServer.js', params: { 'action': 'findData', 'date': $scope.year + '-' + $scope.month + '-' + $scope.day } }).success(function (data) {
            //干粉总数据初始化
            var GFO = [];
            var GFT = [];
            //console.log(data);
            var Category = [];
            var productData = [0,0,0,0];
            var index;
            while(data[0].CategoryID){
                Category.push(data.shift());
            }
            console.log(Category);
            console.log(data);
  /*          for(i in data){
                for(j in Category)
                {
                    if(data[i].ProductID == Category[j].ProductID)
                    { 
                        switch(Category[j].CategoryID)
                        {
                            case "干粉一" : index = 0;break;
                            case "干粉三" : index = 1;break;
                            case "瓷土" : index = 2;break;
                            case "无漂土" : index = 3;break;
                        }
                        switch(data[i].BanciID)
                        {
                            case "零班" : data[i].BanciID = 'L';break;
                            case "中班" : data[i].BanciID = 'Z';break;
                            case "白班" : data[i].BanciID = 'B';break;
                        }
                        switch(data[i].YaopinID)
                        {
                            case "硫酸" : data[i].YaopinID = 'LS';break;
                            case "保险粉" : data[i].YaopinID = 'BXF';break;
                            case "六偏" : data[i].YaopinID = 'LP';break;
                            case "液碱" : data[i].YaopinID = 'YJ';break;
                        }
                        if(productData[index]!=0)
                        {
                            for(k in productData[index])
                            {
                                if(productData[index][k].ProductID==data[i].ProductID)
                                {
                                    if(data[i].ProductOutput)
                                    {
                                       productData[index][k][data[i].BanciID+'RealYield'] = eval(data[i].ProductOutput); 
                                       productData[index][k][data[i].BanciID+'Beizhu'] = data[i].ProductRemark; 
                                    }
                                    else if(data[i].ProductDayPlan)
                                        productData[index][k]['PlanYield'] = eval(data[i].ProductDayPlan); 
                                    else
                                        productData[index][k][data[i].BanciID+data[i].YaopinID] = eval(data[i].YaopinUse);
                                    break;
                                }
                                else if(k ==productData[index].length-1)
                                {
                                    productData[index].push({
                                        ProductID : data[i].ProductID,
                                        CategoryID : Category[j].CategoryID
                                    });
                                    if(data[i].ProductOutput)
                                    {
                                        productData[index][k][data[i].BanciID+'RealYield'] = eval(data[i].ProductOutput); 
                                        productData[index][k][data[i].BanciID+'Beizhu'] = data[i].ProductRemark;  
                                    }
                                    else if(data[i].ProductDayPlan)
                                        productData[index][k]['PlanYield'] = eval(data[i].ProductDayPlan); 
                                    else
                                        productData[index][productData[index].length-1][data[i].BanciID+data[i].YaopinID] = eval(data[i].YaopinUse);
                                }
                            }
                        }
                        else
                        {
                            productData[index]=[];
                            productData[index].push({
                                ProductID : data[i].ProductID,
                                CategoryID : Category[j].CategoryID
                            });
                            if(data[i].ProductOutput)
                            {
                                productData[index][0][data[i].BanciID+'RealYield'] = eval(data[i].ProductOutput); 
                                productData[index][0][data[i].BanciID+'Beizhu'] = data[i].ProductRemark;  
                            }
                            else if(data[i].ProductDayPlan)
                                productData[index][0]['PlanYield'] = eval(data[i].ProductDayPlan); 
                            else
                                productData[index][0][data[i].BanciID+data[i].YaopinID] = eval(data[i].YaopinUse);
                        }
                        break; 
                    }
                }
            }*/
/*           //数据类型转换
            data = chartFn.ChangeType(data);
            for (i in data) {
                data[i] = chartFn.ProductDataInit(data[i]);
            }
             //数据区分
            for (i in productData) {
                switch (productData[i][0].CategoryID) {
                    case "干粉一": AllData(productData[i], "Gf"); $scope.GfProduction =productData[i]; GFO = productData[i]; break;
                    case "干粉三": AllData(productData[i], "Gft"); $scope.GftProduction = productData[i]; GFT = productData[i]; break;
                    case "瓷土": AllData(productData[i], "Ct"); $scope.CtProduction = productData[i];  break;
                    case "无漂土": AllData(productData[i], "Wpt"); $scope.WptProduction = productData[i];  break;
                }
            }
            //干粉总数据计算
            GfDataShow(productData[0], productData[1]);
            $scope.title = "茂名高科" + $scope.year + "年" + $scope.month + "月生产、用药情况";
            $scope.unit = $scope.day + "日情况";
            //表格显示
            $scope.bodyPage = { "padding-top": "0px" };
            $scope.nav = true;
            $scope.form = false;
            $scope.mywait = { 'display': 'none' };
            if (OK != 0) {
                $scope.mywait = { 'display': 'none' };
            }
            OK = 1;*/
        });
        if (begin == 0) {
            $scope.productSelect = "请选择产品明细";
            month = $scope.month;
            var ismonth = $scope.month - 1;
            var lastmaxday;
            var lastyearmaxday = new Date($scope.year-1,$scope.month,0).getDate();
            if (ismonth == 0) {
                lastmaxday = new Date($scope.year, 12, 0).getDate();
            }
            else {
                lastmaxday = new Date($scope.year, ismonth, 0).getDate();
            }
            //$http({ method: 'POST', url: '../createServer.js', params: { 'action': "getMonthPlan", 'year': $scope.year, 'month': $scope.month, 'thisMonthMaxDay': new Date($scope.year, $scope.month, 0).getDate(), 'lastMonthMaxDay': lastmaxday } }).success(function (data) {
                //等待图标隐藏，发送highchart请求
 /*               data = changeData(data);
                begin = data;
                showMonthChart("container", chartFn.addData(seriesName, begin), begin[0], '月累计产品产量（环比）');*/
                //console.log(data);
            //});
            //$http({ method: 'POST', url: '../createServer.js', params: { 'action': "getYearsComparation", 'year': $scope.year, 'month': $scope.month, 'thisMonthMaxDay': new Date($scope.year, $scope.month, 0).getDate(), 'lastYearMaxDay': new Date($scope.year - 1, $scope.month, 0).getDate() } }).success(function (data) {
/*                data = changeYearData(data);
                yearsbegin = data;
                showMonthChart("yearsComparation", chartFn.addData(seriesYearName, yearsbegin), yearsbegin[0], '月累计产品产量（同比）');
                $scope.mywait = { 'display': 'none' };*/
                //console.log(data);
            //});
           
        }
        else if (OK == 0) {
            OK = 1;
            if ($scope.productSelect == "返回各类产量" || $scope.productSelect == "请选择产品明细")
            {
                month = $scope.month;
                var ismonth = $scope.month - 1;
                var lastmaxday;
                if (ismonth == 0) {
                    lastmaxday = new Date($scope.year, 12, 0).getDate();
                }
                else {
                    lastmaxday = new Date($scope.year, ismonth, 0).getDate();
                }
                //$http({ method: 'POST', url: '../createServer.js', params: { 'action': "getMonthPlan", 'year': $scope.year, 'month': $scope.month, 'thisMonthMaxDay': new Date($scope.year, $scope.month, 0).getDate(), 'lastMonthMaxDay': lastmaxday } }).success(function (data) {
/*                    //等待图标隐藏，发送highchart请求
                    data = changeData(data);
                    begin = data;
                    showMonthChart("container", chartFn.addData(seriesName, begin), begin[0], '月累计产品产量(环比)');
                    $('.wait').css("display", 'none');*/
                    //console.log(data);
                //});
            }
            else
            {
                //$http({ method: 'POST', url: '../createServer.js', params: { 'action': 'findDataDetail', 'MonthEnd': $scope.year + '-' + $scope.month + '-' + new Date($scope.year, $scope.month, 0).getDate(), 'monthStart': $scope.year + '-' + $scope.month + '-' + '1', 'product': $scope.productSelect } }).success(function (data) {
/*                    data = DataNameChange(data);
                    data = chartFn.detailSettle(data, $scope.productSelect);
                    detail = data;
                    $scope.productDetails = [{ productDetails: "干粉" }, { productDetails: "瓷土" }, { productDetails: "无漂土" }, { productDetails: "返回各类产量" }];
                    showMonthChart("container", data[0], data[1], '月累计药品用量');
                    $scope.mywait = { 'display': 'none' };
                    if (!data)
                        alert("这个月无此类产品药品用量");*/
                    //console.log(data);
                //});
            }
            //$http({ method: 'POST', url: '../createServer.js', params: { 'action': "getYearsComparation", 'year': $scope.year, 'month': $scope.month, 'thisMonthMaxDay': new Date($scope.year, $scope.month, 0).getDate(), 'lastYearMaxDay': new Date($scope.year - 1, $scope.month, 0).getDate() } }).success(function (data) {
 /*               data = changeYearData(data);
                yearsbegin = data;
                showMonthChart("yearsComparation", chartFn.addData(seriesYearName, yearsbegin), yearsbegin[0], '月累计产品产量（同比）');
                $scope.mywait = { 'display': 'none' };*/
                //console.log(data);
            //});
        }
    }
    function AllData(data, product) {
        //干粉一数据初始化
        eval("$scope." + product + "ProductOutput = 0;");
        eval("$scope." + product + "Sum = 0;");
        eval("$scope." + product + "LsCost = 0;");
        eval("$scope." + product + "BxfCost = 0;");
        eval("$scope." + product + "LpCost = 0;");
        eval("$scope." + product + "DcCost = 0;");
        eval("$scope." + product + "YjCost = 0;");
        //干粉数据导入
        eval("$scope." + product + "Production = data");
        //// 干粉数据计算
        for (x in data) {
            eval("$scope." + product + "ProductOutput += data[x].LRealYield + data[x].BRealYield + data[x].ZRealYield;")
            eval("$scope." + product + "Sum += data[x].PlanYield;")
            eval("$scope." + product + "LsCost += data[x].LLS * data[x].LRealYield + data[x].BLS * data[x].BRealYield + data[x].ZLS * data[x].ZRealYield;")
            eval("$scope." + product + "BxfCost += data[x].LBXF * data[x].LRealYield + data[x].BBXF * data[x].BRealYield + data[x].ZBXF * data[x].ZRealYield;")
            eval("$scope." + product + "LpCost += data[x].LLP * data[x].LRealYield + data[x].BLP * data[x].BRealYield + data[x].ZLP * data[x].ZRealYield;")
            eval("$scope." + product + "DcCost += data[x].LDC * data[x].LRealYield + data[x].BDC * data[x].BRealYield + data[x].ZDC * data[x].ZRealYield;")
            eval("$scope." + product + "YjCost += data[x].LYJ * data[x].LRealYield + data[x].BYJ * data[x].BRealYield + data[x].ZYJ * data[x].ZRealYield;")
        }
        eval("$scope." + product + "LsCost /= $scope.GfProductOutput;")
        eval("$scope." + product + "BxfCost /= $scope.GfProductOutput;")
        eval("$scope." + product + "LpCost /= $scope.GfProductOutput;")
        eval("$scope." + product + "DcCost /= $scope.GfProductOutput;")
        eval("$scope." + product + "YjCost /= $scope.GfProductOutput;")
    }
    //干粉总数据展现
    function GfDataShow(Gfdata, Gftdata) {
        // 干粉总数据计算
        if ((Gftdata.length > 1) && (Gfdata.length > 1)) {
            $scope.GFSum = $scope.GfSum + $scope.GftSum;
            $scope.GFProductOutput = $scope.GfProductOutput + $scope.GftProductOutput;
            $scope.GFLsCost = ($scope.GfLsCost * $scope.GfProductOutput + $scope.GftLsCost * $scope.GftProductOutput) / ($scope.GfProductOutput + $scope.GftProductOutput);
            $scope.GFBxfCost = ($scope.GfBxfCost * $scope.GfProductOutput + $scope.GftBxfCost * $scope.GftProductOutput) / ($scope.GfProductOutput + $scope.GftProductOutput);
            $scope.GFLpCost = ($scope.GfLpCost * $scope.GfProductOutput + $scope.GftLpCost * $scope.GftProductOutput) / ($scope.GfProductOutput + $scope.GftProductOutput);
            $scope.GFDcCost = ($scope.GfDcCost * $scope.GfProductOutput + $scope.GftDcCost * $scope.GftProductOutput) / ($scope.GfProductOutput + $scope.GftProductOutput);
            $scope.GFYjCost = ($scope.GfYjCost * $scope.GfProductOutput + $scope.GftYjCost * $scope.GftProductOutput) / ($scope.GfProductOutput + $scope.GftProductOutput);
        }
        else if (Gfdata.length > 1) {
            $scope.GFSum = $scope.GfSum;
            $scope.GFProductOutput = $scope.GfProductOutput;
            $scope.GFLsCost = $scope.GfLsCost;
            $scope.GFBxfCost = $scope.GfBxfCost;
            $scope.GFLpCost = $scope.GfLpCost;
            $scope.GFDcCost = $scope.GfDcCost;
            $scope.GFYjCost = $scope.GfYjCost;
        }
        else {
            $scope.GFSum = $scope.GftSum;
            $scope.GFProductOutput = $scope.GftProductOutput;
            $scope.GFLsCost = $scope.GftLsCost;
            $scope.GFBxfCost = $scope.GftBxfCost;
            $scope.GFLpCost = $scope.GftLpCost;
            $scope.GFDcCost = $scope.GftDcCost;
            $scope.GFYjCost = $scope.GftYjCost;
        }
    }

}]);
