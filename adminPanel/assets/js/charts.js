document.addEventListener('DOMContentLoaded', function() {

    function calculateProfit(income, pending, expenses){
        return income.map((v,i) => v - (pending[i] + expenses[i]));
    }

    // ------------------ Revenue Chart ------------------
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    function getRevenueData(timeframe){
        switch(timeframe){
            case 'daily': return dailyData;
            case 'weekly': return weeklyData;
            case 'monthly': return monthlyData;
            case 'yearly': return yearlyData;
            default: return dailyData;
        }
    }
    let currentRevenue = getRevenueData('daily');
    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: { 
            labels: currentRevenue.labels, 
            datasets: [{ 
                label:'Revenue', 
                data: currentRevenue.values, 
                backgroundColor:'#28a745' 
            }] 
        },
        options:{
            responsive:true,
            plugins:{
                tooltip:{enabled:true},
                title:{display:true,text:''}
            },
            scales:{y:{beginAtZero:true}}
        }
    });
    document.getElementById('timeframe').addEventListener('change', function(){
        const newData = getRevenueData(this.value);
        revenueChart.data.labels = newData.labels;
        revenueChart.data.datasets[0].data = newData.values;
        revenueChart.update();
    });

    // ------------------ Booking Status Chart ------------------
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type:'doughnut',
        data:{ labels:['New','Accepted','Rejected'], datasets:[{ data:statusData, backgroundColor:['#ffc107','#28a745','#dc3545'] }] },
        options:{ responsive:true, plugins:{title:{display:true,text:'Booking Status'}} }
    });

    // ------------------ Financial Overview Chart ------------------
    const financeCtx = document.getElementById('financeChart').getContext('2d');
    function getFinanceData(timeframe){ return financeData[timeframe] || financeData.daily; }
    let currentFinanceData = getFinanceData('daily');

    const financeChart = new Chart(financeCtx, {
        type:'line',
        data:{
            labels: currentFinanceData.labels,
            datasets:[
                { label:'Income', data: currentFinanceData.income, borderColor:'#28a745', backgroundColor:'#28a74533', fill:true, tension:0.3, pointRadius:6, pointHoverRadius:8 },
                { label:'Pending', data: currentFinanceData.pending, borderColor:'#ffc107', backgroundColor:'#ffc10733', fill:true, tension:0.3, pointRadius:6, pointHoverRadius:8 },
                { label:'Expenses', data: currentFinanceData.expenses, borderColor:'#dc3545', backgroundColor:'#dc354533', fill:true, tension:0.3, pointRadius:6, pointHoverRadius:8 },
                { label:'Profit', data: calculateProfit(currentFinanceData.income,currentFinanceData.pending,currentFinanceData.expenses), borderColor:'#007bff', backgroundColor:'#007bff33', fill:true, tension:0.3, pointRadius:6, pointHoverRadius:8 }
            ]
        },
        options:{
            responsive:true,
            interaction:{ mode:'index', intersect:false },
            plugins:{
                tooltip:{
                    enabled:true,
                    displayColors:true,
                    callbacks:{
                        title: ctx => ctx[0].label, // Show date on top
                        label: ctx => `${ctx.dataset.label}: $${ctx.raw.toLocaleString()}` // Show value with label
                    }
                }
            },
            scales:{ y:{ beginAtZero:true } }
        }
    });

    document.getElementById('financeTimeframe').addEventListener('change', function(){
        currentFinanceData = getFinanceData(this.value);
        financeChart.data.labels = currentFinanceData.labels;
        financeChart.data.datasets[0].data = currentFinanceData.income;
        financeChart.data.datasets[1].data = currentFinanceData.pending;
        financeChart.data.datasets[2].data = currentFinanceData.expenses;
        financeChart.data.datasets[3].data = calculateProfit(currentFinanceData.income,currentFinanceData.pending,currentFinanceData.expenses);
        financeChart.update();
    });

});