$(window).on("load",(function(){new Chartist.Line(".simple-line-chart",{labels:["Monday","Tuesday","Wednesday","Thursday","Friday"],series:[[12,9,7,8,5],[2,1,3.5,7,3],[1,3,4,5,6]]},{fullWidth:!0,chartPadding:{right:40}}),new Chartist.Line(".line-area-chart",{labels:[1,2,3,4,5,6,7,8],series:[[5,9,7,8,5,3,5,4]]},{low:0,showArea:!0}),new Chartist.Line(".bi-polar-chart",{labels:[1,2,3,4,5,6,7,8],series:[[1,2,3,1,-2,0,1,0],[-2,-1,-2,-1,-2.5,-1,-2,-1],[0,0,0,1,2,2.5,2,1],[2.5,2,1,.5,1,.5,-1,-2.5]]},{high:3,low:-3,showArea:!0,showLine:!1,showPoint:!1,fullWidth:!0,axisX:{showLabel:!1,showGrid:!1}});var e=new Chartist.Line(".series-overrides-chart",{labels:["1","2","3","4","5","6","7","8"],series:[{name:"series-1",data:[5,2,-4,2,0,-2,5,-3]},{name:"series-2",data:[4,3,5,3,1,3,6,4]},{name:"series-3",data:[2,4,3,1,4,5,3,2]}]},{fullWidth:!0,series:{"series-1":{lineSmooth:Chartist.Interpolation.step()},"series-2":{lineSmooth:Chartist.Interpolation.simple(),showArea:!0},"series-3":{showPoint:!1}}},[["screen and (max-width: 320px)",{series:{"series-1":{lineSmooth:Chartist.Interpolation.none()},"series-2":{lineSmooth:Chartist.Interpolation.none(),showArea:!1},"series-3":{lineSmooth:Chartist.Interpolation.none(),showPoint:!0}}}]]),a={labels:["W1","W2","W3","W4","W5","W6","W7","W8","W9","W10"],series:[[1,2,4,8,6,-2,-1,-4,-6,-2]]},t={high:10,low:-10,axisX:{labelInterpolationFnc:function(e,a){return a%2==0?e:null}}};new Chartist.Bar(".bi-polar-bar-chart",a,t),new Chartist.Bar(".stacked-bar-chart",{labels:["Q1","Q2","Q3","Q4"],series:[[8e5,12e5,14e5,13e5],[2e5,4e5,5e5,3e5],[1e5,2e5,4e5,6e5]]},{stackBars:!0,axisY:{labelInterpolationFnc:function(e){return e/1e3+"k"}}}).on("draw",(function(e){"bar"===e.type&&e.element.attr({style:"stroke-width: 30px"})})),new Chartist.Bar(".horizontal-bar-chart",{labels:["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],series:[[5,4,3,7,5,10,3],[3,2,9,5,4,6,4]]},{seriesBarDistance:10,reverseData:!0,horizontalBars:!0,axisY:{offset:70}}),new Chartist.Bar(".multi-line-chart",{labels:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],series:[[5,4,3,7,5,10,3,4,8,10,6,8],[3,2,9,5,4,6,4,6,7,8,7,4],[2,1,7,3,2,4,2,3,4,3,5,1]]},{seriesBarDistance:10,axisX:{offset:60},axisY:{offset:80,labelInterpolationFnc:function(e){return e},scaleMinSpace:15}});a={series:[5,3,4]};var n=function(e,a){return e+a};new Chartist.Pie(".pie-chart",a,{labelInterpolationFnc:function(e){return Math.round(e/a.series.reduce(n)*100)+"%"}});a={labels:["Bananas","Apples","Grapes"],series:[20,15,40]},t={labelInterpolationFnc:function(e){return e[0]}};new Chartist.Pie(".pie-custome-label-chart",a,t,[["screen and (min-width: 991px)",{chartPadding:30,labelOffset:100,labelDirection:"explode",labelInterpolationFnc:function(e){return e}}],["screen and (min-width: 992px) ",{labelOffset:100,chartPadding:50}]]),(e=new Chartist.Pie(".animation-chart",{series:[10,20,50,20,30,5,15,20],labels:[1,2,3,4,5,6,7,8]},{donut:!0,showLabel:!0})).on("draw",(function(e){if("slice"===e.type){var a=e.element._node.getTotalLength();e.element.attr({"stroke-dasharray":a+"px "+a+"px"});var t={"stroke-dashoffset":{id:"anim"+e.index,dur:1e3,from:-a+"px",to:"0px",easing:Chartist.Svg.Easing.easeOutQuint,fill:"freeze"}};0!==e.index&&(t["stroke-dashoffset"].begin="anim"+(e.index-1)+".end"),e.element.attr({"stroke-dashoffset":-a+"px"}),e.element.animate(t,!1)}})),e.on("created",(function(){window.__anim21278907124&&(clearTimeout(window.__anim21278907124),window.__anim21278907124=null),window.__anim21278907124=setTimeout(e.update.bind(e),1e4)})),new Chartist.Pie(".gauge-chart",{series:[20,10,30,40]},{donut:!0,donutWidth:60,donutSolid:!0,startAngle:270,total:200,showLabel:!0})}));
