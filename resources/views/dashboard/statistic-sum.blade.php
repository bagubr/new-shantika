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