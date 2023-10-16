<template>
    <div>
        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#report">
            Sell Report
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8">
                </polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </button>

        <div class="modal fade" id="report" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content rounded-0 border-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quandide</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in report">
                                <td>{{ item.item_name }}</td>
                                <td>{{ item.quantidade }}</td>
                                <td>{{ item.total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SellReportComponent',

    data() {
        return {
            report: null
        }
    },

    methods: {
        getReport(){
            axios.get('/api/dashboard/report').then((response) => {
                console.log(response.data)
                this.report = response.data
            }).catch((errors) =>{
                console.log(errors)
            })
        }
    },

    mounted(){
        this.getReport()
    }
}
</script>