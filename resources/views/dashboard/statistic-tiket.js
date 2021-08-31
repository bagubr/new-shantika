$(function(){
    var areaChartData = {
    labels  : $data['params'],
    datasets: [
    {
        label               : 'Jawa',
        backgroundColor     : '#17a2b8',
        borderColor         : '#17a2b8',
        pointRadius         : false,
        pointColor          : '#17a2b8',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        data                : $data['tiket']
    },
    {
        label               : 'Jabodetabek',
        backgroundColor     : '#c1c7d1',
        borderColor         : '#c1c7d1',
        pointRadius         : false,
        pointColor          : '#c1c7d1',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        data                : $data['tiket'],
    },
    ]
}
        //-------------
//- BAR CHART -
//-------------
var barChartCanvas = $('#barChart').get(0).getContext('2d')
var barChartData = $.extend(true, {}, areaChartData)
var temp0 = areaChartData.datasets[0]
// barChartData.datasets[1] = temp0

var barChartOptions = {
    responsive              : true,
    maintainAspectRatio     : false,
    datasetFill             : false
}

new Chart(barChartCanvas, {
    type: 'bar',
    data: barChartData,
    options: barChartOptions
})
})
$(document).on('change', '.statistic-tiket', function (e) {
    console.log(this.value);
    $.ajax({
        url: "{{url('dashboard/statistic-tiket')}}",
        type: "POST",
        data: {
            _token: '{{ csrf_token() }}',
            params: this.value,
        },
        success: function (data) {
            window.myChart.data.labels = data['labels'];
            window.myChart.data.datasets[0].data = data['now'];
            window.myChart.data.datasets[1].data = data['previous'];
            window.myChart.update();
            console.log(data['labels'])
        },
    });
});