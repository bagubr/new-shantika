var app = new Vue({
    el: '#app',
    data: {
        message: 'Hello Vue!',
        test: [],
        area_id: '',
        agency: [{
            id: '',
            name: '',
        }]
    },
    methods: {
        getData() {
            axios
                .get('/agency/all', {
                    params: {
                        area_id: this.area_id
                    },
                })
                .then(result => {
                    this.test = result.data.agencies
                });
        },
    },
})