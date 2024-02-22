

function updateChartData(data) {

    var ctx = $('.myChart').getContext('2d');

    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(function(d) {
                return d.date;
            }),
            datasets: [{
                    label: 'Documents',
                    data: data.map(function(d) {
                        return d.count;
                    }),
                    fill: 'true',
                    borderColor: 'darkviolet',
                    borderWidth: 1,
                    yAxisID: 'count-axis', // Assign this dataset to the 'count-axis'
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: false,
            },
            scales: {
                yAxes: [{
                    id: 'count-axis',
                    position: 'left',
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1,
                        display: false, 
                    },
                }, ],
            },
        },
    });
}

fetch('getStatsAll.php')
    .then(response => response.json())
    .then(data => {
        updateChartData(data);
    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });

function reload() {
    fetch('getStatsAll.php')
        .then(response => response.json())
        .then(data => {
            updateChartData(data);
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}

setInterval(reload, 10000);