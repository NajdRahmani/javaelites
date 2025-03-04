document.addEventListener("DOMContentLoaded", function () {
    var dom = document.getElementById('chart-container');
    var myChart = echarts.init(dom);

    var quizNames = quizStats.map(q => q.name);
    var avgScores = quizStats.map(q => q.averageScore);
    var maxScores = quizStats.map(q => q.maxScore);
    var minScores = quizStats.map(q => q.minScore);

    var option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross'
            }
        },
        toolbox: {
            feature: {
                dataView: { show: true, readOnly: false },
                magicType: { show: true, type: ['line', 'bar'] },
                restore: { show: true },
                saveAsImage: { show: true }
            }
        },
        legend: {
            data: ['Average Score', 'Max Score', 'Min Score']
        },
        xAxis: {
            type: 'category',
            data: quizNames
        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
                name: 'Average Score',
                type: 'bar',
                data: avgScores
            },
            {
                name: 'Max Score',
                type: 'bar',
                data: maxScores
            },
            {
                name: 'Min Score',
                type: 'line',
                data: minScores
            }
        ]
    };

    myChart.setOption(option);
    window.addEventListener('resize', myChart.resize);
});
