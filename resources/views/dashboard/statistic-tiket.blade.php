<script>
    const dataNow = {
        labels: [@foreach ($data['now']['labels'] as  $value)"{{$value}}",@endforeach],
        datasets: [{
            label           : 'Jawa',
            data            : [@foreach ($data['now']['data'][0] as $value){{$value}},@endforeach],
            backgroundColor : 'rgb(255, 99, 132)',
            borderColor     : 'rgb(255, 99, 132)',
        },
        {
            label           : 'Jabodetabek',
            backgroundColor : 'rgb(253, 206, 18)',
            borderColor     : 'rgb(253, 206, 18)',
            data            : [@foreach ($data['now']['data'][1] as $value){{$value}},@endforeach],
        },
        ]
    };
    const dataPrevious = {
        labels: [@foreach ($data['now']['labels'] as  $value)"{{$value}}",@endforeach],
        datasets: [{
            label           : 'Jawa',
            data            : [@foreach ($data['previous']['data'][0] as $value){{$value}},@endforeach],
            backgroundColor : 'rgb(255, 99, 132)',
            borderColor     : 'rgb(255, 99, 132)',
        },
        {
            label           : 'Jabodetabek',
            backgroundColor : 'rgb(253, 206, 18)',
            borderColor     : 'rgb(253, 206, 18)',
            data            : [@foreach ($data['previous']['data'][1] as $value){{$value}},@endforeach],
        },
        ]
    };
    const OptionNow = {
        type: 'bar',
        data: dataNow,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
    };
    const OptionPrevious = {
        type: 'bar',
        data: dataPrevious,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
    };
    window.ChartNow = new Chart(
        document.getElementById("ChartNow"),
        OptionNow
    );
    window.ChartPrevious = new Chart(
        document.getElementById("ChartPrevious"),
        OptionPrevious
    );
</script>
<script>
    $(document).on('change', '.statistic', function (e) {
        var params = this.value;
        $.ajax({
            url: "{{url('dashboard/dashboard')}}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                params: params,
            },
            success: function (data) {
                console.log(data);
                $('.change-statistic').attr('data-digit', 0);
                if(params == 'weekly'){
                    $('.change-statistic-previous').attr('data-digit', -7);
                }else{
                    $('.change-statistic-previous').attr('data-digit', -12);
                }
                window.ChartNow.data.labels = data['now']['labels'];
                window.ChartPrevious.data.labels = data['previous']['labels'];
                window.ChartNow.data.datasets[0].data = data['now']['data'][0];
                window.ChartNow.data.datasets[1].data = data['now']['data'][1];
                window.ChartPrevious.data.datasets[0].data = data['previous']['data'][0];
                window.ChartPrevious.data.datasets[1].data = data['previous']['data'][1];
                $('.label-now').html(data['now']['data']['label']);
                $('.label-previous').html(data['previous']['data']['label']);
                window.ChartNow.update();
                window.ChartPrevious.update();
            },
        });
    });
</script>

<script>
    $(document).on('click', '.change-statistic', function (e) {
        var params = $('.statistic').val();
        if(params == 'weekly'){
            var digit_now = parseInt($(this).attr('data-digit')) - 7;
        }else{
            var digit_now = parseInt($(this).attr('data-digit')) - 12;
        }

        $.ajax({
            url: "{{url('dashboard/dashboard')}}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                params: params,
                digit: digit_now,
            },
            success: function (data) {
                console.log(data);
                window.ChartNow.data.labels = data['now']['labels'];
                window.ChartPrevious.data.labels = data['previous']['labels'];
                window.ChartNow.data.datasets[0].data = data['now']['data'][0];
                window.ChartNow.data.datasets[1].data = data['now']['data'][1];
                window.ChartPrevious.data.datasets[0].data = data['previous']['data'][0];
                window.ChartPrevious.data.datasets[1].data = data['previous']['data'][1];
                $('.label-now').html(data['now']['data']['label']);
                $('.label-previous').html(data['previous']['data']['label']);
                $('.change-statistic').attr('data-digit', digit_now);
                $('.change-statistic-previous').attr('data-digit', digit_now);
                window.ChartNow.update();
                window.ChartPrevious.update();
            },
        });
    });
</script>

<script>
    $(document).on('click', '.change-statistic-previous', function (e) {
        var params = $('.statistic').val();
        if(params == 'weekly'){
            var digit_now = parseInt($(this).attr('data-digit')) + 7;
        }else{
            var digit_now = parseInt($(this).attr('data-digit')) + 12;
        }
        $.ajax({
            url: "{{url('dashboard/dashboard')}}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                params: params,
                digit: digit_now,
            },
            success: function (data) {
                console.log(data);
                window.ChartNow.data.labels = data['now']['labels'];
                window.ChartPrevious.data.labels = data['previous']['labels'];
                window.ChartNow.data.datasets[0].data = data['now']['data'][0];
                window.ChartNow.data.datasets[1].data = data['now']['data'][1];
                window.ChartPrevious.data.datasets[0].data = data['previous']['data'][0];
                window.ChartPrevious.data.datasets[1].data = data['previous']['data'][1];
                $('.label-now').html(data['now']['data']['label']);
                $('.label-previous').html(data['previous']['data']['label']);
                $('.change-statistic').attr('data-digit', digit_now);
                $('.change-statistic-previous').attr('data-digit', digit_now);
                window.ChartNow.update();
                window.ChartPrevious.update();
            },
        });
    });
</script>