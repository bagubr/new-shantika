
<script>
    const dataAgent = {
        labels: [@foreach ($data_agent['agencies_area'] as $key => $value)"{{$value}}",@endforeach],
        datasets: [{
            label           : 'Agent',
            data            : [@foreach ($data_agent['agent']['data'] as $value){{$value}},@endforeach],
            backgroundColor : 'rgb(255, 99, 132)',
            borderColor     : 'rgb(255, 99, 132)',
        },
        ]
    };
    const OptionAgent = {
        type: 'bar',
        data: dataAgent,
        options: {
            indexAxis: 'y',
            elements: {
                bar: {
                    borderWidth: 10,
                }
            },
            scaleShowValues: true,
            scales: {
                y: {
                    ticks: {
                    autoSkip: false,
                    }
                },
                yAxes: [{
                    position: 'top',
                    ticks: {
                        padding: 10
                    }
                }],
            }
        },
    };
    window.ChartAgent = new Chart(
        document.getElementById("ChartAgent"),
        OptionAgent
    );
</script>
<script>
    $(document).on('change', '.change-statistic-area', function (e) {
        let name = this.name
        let form = {
            area_id: null,
            year: null,
            month: null
        }
        switch (name) {
            case 'area_id':
                form.area_id = this.value
                break;
            case 'year':
                form.year = this.value
                break;
            case 'month':
                form.month = this.value
                break;
        }
        $.ajax({
            url: "{{url('dashboard/agent')}}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                ...form
            },
            success: function (data) {
                console.log(data);
                window.ChartAgent.data.labels = data['agencies_area'];
                window.ChartAgent.data.datasets[0].data = data['agent']['data'] || [];
                window.ChartAgent.update();
            },
            beforeSend: function() {
                Pace.restart();
            }
        });
    });
</script>