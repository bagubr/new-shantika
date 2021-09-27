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
                    borderWidth: 2,
                }
            },
        },
    };
    window.ChartAgent = new Chart(
        document.getElementById("ChartAgent"),
        OptionAgent
    );
</script>
<script>
    $(document).on('change', '.change-statistic-sum', function (e) {
        let name = this.name
        let form = {
            year: null,
            month: null
        }
        switch (name) {
            case 'year':
                form.year = this.value
                break;
            case 'month':
                form.month = this.value
                break;
        }
        $.ajax({
            url: "{{url('dashboard/sum')}}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                ...form
            },
            success: function (data) {
                console.log(data)
                document.querySelector('#sum-total-order').innerHTML = data.sum_total_order;
                document.querySelector('#sum-count-user').innerHTML = data.sum_count_user;
                document.querySelector('#sum-count-money').innerHTML = data.sum_count_money;
            },
            beforeSend: function() {
                Pace.restart();
            }
        });
    });
</script>