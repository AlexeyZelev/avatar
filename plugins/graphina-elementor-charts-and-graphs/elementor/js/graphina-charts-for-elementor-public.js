(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(window).load(() => {
        let ele = $('.chart-card');
        addRemoveClass(ele.find('h4'), 'graphina-chart-heading');
        addRemoveClass(ele.find('p'), 'graphina-chart-sub-heading');
    })

})(jQuery);


/***********************
 * Variables
 */
if (typeof fadein == "undefined") {
    var fadein = {};
}
if (typeof fadeout == "undefined") {
    var fadeout = {};
}
if (typeof isInit == "undefined") {
    var isInit = {};
}

/***************
 * Jquery Base
 * @param ele
 * @param add
 * @param remove
 */

function addRemoveClass(ele, add = '', remove = '') {
    if (remove !== '' && ele.hasClass(add)) ele.removeClass(remove);
    if (add !== '' && !ele.hasClass(add)) ele.addClass(add);
}

/************
 *
 * @param timestamp
 * @param isTime
 * @param isDate
 * @returns {string}
 */

function dateFormat(timestamp, isTime = false, isDate = false) {
    let dateSeparator = '-';
    let date = new Date(timestamp);
    let hours = date.getHours();
    let minutes = "0" + date.getMinutes();
    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear()
    return (isDate ? (day + dateSeparator + month + dateSeparator + year) : '') + (isDate && isTime ? ' ' : '') + (isTime ? (hours + ':' + minutes.substr(-2)) : '');
}

/********************
 *
 * @param date1
 * @param date2
 * @returns {string}
 */
function timeDifference(date1, date2) {
    let difference = new Date(date2).getTime() - new Date(date1).getTime();

    let daysDifference = Math.floor(difference / 1000 / 60 / 60 / 24);
    difference -= daysDifference * 1000 * 60 * 60 * 24

    let hoursDifference = Math.floor(difference / 1000 / 60 / 60);
    difference -= hoursDifference * 1000 * 60 * 60

    let minutesDifference = Math.floor(difference / 1000 / 60);

    return getPostfix(daysDifference, 'day', 'days')
        + (daysDifference > 0 && hoursDifference > 0 ? ' ' : '')
        + getPostfix(hoursDifference, 'hour', 'hours')
        + (hoursDifference > 0 && minutesDifference > 0 ? ' ' : '')
        + getPostfix(minutesDifference, 'minute', 'minutes');
}

/*********************
 *
 * @param value
 * @param postfix1
 * @param postfix2
 * @returns {string}
 */
function getPostfix(value, postfix1, postfix2) {
    let result = ''
    switch (true) {
        case value === 0:
            break;
        case value === 1:
            result += value + ' ' + postfix1;
            break;
        case value > 1:
            result += value + ' ' + postfix2;
            break;
    }
    return result;
}


/*******************
 *
 * @param svg
 * @param mainId
 */

function adjustSize(svg, mainId) {
    const innerHeight = getInnerHeightWidth(document.querySelector('#animated-radial_' + mainId), 'height');
    const innerWidth = getInnerHeightWidth(document.querySelector('#animated-radial_' + mainId), 'width');
    svg[mainId].attr("width", innerWidth).attr("height", innerHeight);
}

/************************
 *
 * @param elm
 * @param type
 * @returns {number}
 */

function getInnerHeightWidth(elm, type) {
    let computed = getComputedStyle(elm);
    let padding = 0;
    let val = 0;
    switch (type) {
        case 'height':
            padding = parseInt(computed.paddingTop) + parseInt(computed.paddingBottom);
            val = elm.clientHeight - padding;
            break;
        case 'width':
            padding = parseInt(computed.paddingLeft) + parseInt(computed.paddingRight);
            val = elm.clientWidth - padding;
            break;
    }
    return val;
}

/**********************************
 *
 * @param main_id
 * @param n
 * @param g
 * @param interval
 * @param animeSpeed
 * @param bars
 * @param animatedRadialChartHeight
 */

function update(main_id, n = [], g = [], interval = [], animeSpeed = [], bars = [], animatedRadialChartHeight = []) {
    if (typeof g[main_id] === "undefined") {
        clearInterval(interval[main_id]);
    }

    n[main_id] += parseFloat(animeSpeed[main_id]);
    g[main_id].selectAll("rect")
        .data(bars[main_id])
        .attr("width", d => (parseFloat(animatedRadialChartHeight[main_id]) + noise.perlin3(d, 1, n[main_id]) * 160));
}

/****************************************
 *
 * @param main_id
 * @param svg
 * @param g
 * @param bars
 * @param radialGradient
 * @param animatedRadialChartColor
 * @param animatedRadialChartLineSpace
 */

function drawBars(main_id, svg, g, bars, radialGradient, animatedRadialChartColor, animatedRadialChartLineSpace) {
    if (typeof svg[main_id] !== "undefined") {
        d3.selectAll("#animated-radial_" + main_id + " svg > *").remove();
    }
    svg[main_id] = d3.select("#animated-radial_" + main_id + " svg");
    adjustSize(svg, main_id)

    g[main_id] = svg[main_id].append("g").attr("transform", `translate(${960 / 2},${560 / 2})`);

    bars[main_id] = d3.range(0, 120);

    radialGradient[main_id] = svg[main_id]
        .append("defs")
        .append("radialGradient")
        .attr("id", "radial-gradient-" + main_id)
        .attr("gradientUnits", "userSpaceOnUse")
        .attr("cx", 0)
        .attr("cy", 0)
        .attr("r", "30%");

    radialGradient[main_id]
        .append("stop")
        .attr("offset", "60%")
        .attr("stop-color", animatedRadialChartColor[main_id].gradient_1);

    radialGradient[main_id]
        .append("stop")
        .attr("offset", "100%")
        .attr("stop-color", animatedRadialChartColor[main_id].gradient_2);

    g[main_id].append("circle")
        .attr("cx", 0)
        .attr("cy", 0)
        .attr("r", 80)
        .attr("stroke", animatedRadialChartColor[main_id].stroke_color)
        .attr("fill", "none")
        .attr("text", "none");

    g[main_id].selectAll("rect")
        .data(bars[main_id])
        .enter()
        .append("rect")
        .attr("x", 100)
        .attr("y", 0)
        .attr("width", 100)
        .attr("height", 6)
        .attr("fill", "url(#radial-gradient-" + main_id + ")")
        .attr("transform", d => `rotate(${d * animatedRadialChartLineSpace[main_id]})`);
}

/************************************
 *
 * @param mainId
 * @param svg
 * @param g
 * @param bars
 * @param radialGradient
 * @param animatedRadialChartColor
 * @param animatedRadialChartLineSpace
 * @param n
 * @param interval
 * @param animeSpeed
 * @param animatedRadialChartHeight
 */

function initAnimatedRadial(mainId, svg, g, bars, radialGradient, animatedRadialChartColor, animatedRadialChartLineSpace, n, interval, animeSpeed, animatedRadialChartHeight) {
    window.addEventListener("resize", () => {
        adjustSize(svg, mainId)
    });
    drawBars(mainId, svg, g, bars, radialGradient, animatedRadialChartColor, animatedRadialChartLineSpace)
    update(mainId, n, g, interval, animeSpeed, bars, animatedRadialChartHeight);
    if (typeof interval !== undefined && typeof interval[mainId] !== undefined) {
        adjustSize(svg, mainId);
        drawBars(mainId, svg, g, bars, radialGradient, animatedRadialChartColor, animatedRadialChartLineSpace)
        clearInterval(interval[mainId]);
    }
    interval[mainId] = setInterval(function () {
        update(mainId, n, g, interval, animeSpeed, bars, animatedRadialChartHeight);
    }, 50);
}

function isInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
        (
            (rect.top - ((window.innerHeight || document.documentElement.clientHeight) / 2.1)) >= 0 &&
            (rect.bottom - ((window.innerHeight || document.documentElement.clientHeight) / 1.9)) <= (window.innerHeight || document.documentElement.clientHeight)
        )
        ||
        (
            rect.top >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)
        )
    );
}

function initNow(myElement, chart, id) {
    localize.graphinaAllGraphsOptions[id] = chart;
    if (id in localize.graphinaAllGraphs) {
        localize.graphinaAllGraphs[id].destroy();
        delete localize.graphinaAllGraphs[id];
        delete localize.graphinaBlockCharts[id];
    }
    isInit[id] = false;
    getChart(localize.graphinaAllGraphsOptions[id].ele, id);
    document.addEventListener('scroll', function () {
        getChart(localize.graphinaAllGraphsOptions[id].ele, id);
    });
}

function getChart(myElement, id) {
    if (isInViewport(myElement) && (id in isInit) && isInit[id] === false) {
        initGraphinaCharts(id)
    }
}

function initGraphinaCharts(id) {
    if(Object.keys(localize.graphinaBlockCharts).includes(id)){
        if (isInit[id] === true){
            localize.graphinaAllGraphs[id].destroy();
        }
        localize.graphinaAllGraphsOptions[id].ele.innerHTML='';
        localize.graphinaAllGraphsOptions[id].ele.innerHTML = localize.graphinaBlockCharts[id];
    } else {
        if (isInit[id] === true || localize.graphinaAllGraphs[id]) {
            let option = localize.graphinaAllGraphsOptions[id].options;
            let series = option.series;
            delete option.series;
            localize.graphinaAllGraphs[id].updateOptions(option, true, localize.graphinaAllGraphsOptions[id].animation);
            localize.graphinaAllGraphs[id].updateSeries(series, localize.graphinaAllGraphsOptions[id].animation);
        } else {
            localize.graphinaAllGraphs[id] = new ApexCharts(localize.graphinaAllGraphsOptions[id].ele, localize.graphinaAllGraphsOptions[id].options);
            localize.graphinaAllGraphs[id].render();
            isInit[id] = true;
        }
    }
}

function instantInitGraphinaCharts(id) {
    localize.graphinaAllGraphs[id] = new ApexCharts(localize.graphinaAllGraphsOptions[id].ele, localize.graphinaAllGraphsOptions[id].options);
    localize.graphinaAllGraphs[id].render();
}

/**
 * Simple object check.
 * @param item
 * @returns {boolean}
 */
function isObject(item) {
    return (item && typeof item === 'object' && !Array.isArray(item));
}

/**
 * Deep merge two objects.
 * @param target
 * @param sources
 */
function mergeDeep(target, ...sources) {
    if (!sources.length) return target;
    const source = sources.shift();

    if (isObject(target) && isObject(source)) {
        for (const key in source) {
            if (source.hasOwnProperty(key)) {
                if (isObject(source[key])) {
                    if (!target[key]) Object.assign(target, {[key]: {}});
                    mergeDeep(target[key], source[key]);
                } else {
                    Object.assign(target, {[key]: source[key]});
                }
            }
        }
    }

    return mergeDeep(target, ...sources);
}

function chunk(array, size) {
    if (!array) return [];
    const firstChunk = array.slice(0, size);
    if (!firstChunk.length) {
        return array;
    }
    return [firstChunk].concat(chunk(array.slice(size, array.length), size));
}

function getDataForChartsAjax(request_fields, type, id) {
    jQuery.ajax({
        url: localize.ajaxurl,
        type: "post",
        data: {
            action: "get_graphina_chart_settings",
            _ajax_nonce: localize.nonce,
            chart_type: type,
            chart_id: id,
            fields: request_fields
        },
        success: function (response) {
            if (response.status === true) {
                if(response.fail === true){
                    localize.graphinaBlockCharts[response.chart_id] = response.fail_message;
                    initGraphinaCharts(response.chart_id);
                }else {
                    if (response.instant_init === true) {
                        instantInitGraphinaCharts(response.chart_id);
                        localize.graphinaAllGraphsOptions[response.chart_id].animation = false;
                    }
                    localize.graphinaAllGraphsOptions[response.chart_id].options = mergeDeep(localize.graphinaAllGraphsOptions[response.chart_id].options, response.chart_option);
                    if (isInit[response.chart_id] === true) {
                        initGraphinaCharts(response.chart_id);
                    }
                }
            }
        },
        error: function () {
            console.log('fail');
        }
    });
}