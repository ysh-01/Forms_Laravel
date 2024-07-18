// statistics.js

// Function to fetch responses from backend
function fetchResponses() {
    return fetch('/api/responses') // Replace with your actual API endpoint
        .then(response => response.json())
        .catch(error => console.error('Error fetching responses:', error));
}

// Function to process fetched responses data
function processDataForCharts(responses, questions) {
    const pieData = {};
    const barData = {};

    responses.forEach(response => {
        const question = questions[response.question_id];
        const decodedAnswers = JSON.parse(response.answers);

        // Process data for pie chart
        if (!pieData[question.question_text]) {
            pieData[question.question_text] = {};
        }
        if (question.type === 'multiple_choice' || question.type === 'checkbox') {
            JSON.parse(question.options).forEach(option => {
                pieData[question.question_text][option] = (pieData[question.question_text][option] || 0) +
                    (decodedAnswers.includes(option) ? 1 : 0);
            });
        }

        // Process data for bar graph (assuming numerical responses)
        if (question.type === 'short_answer' || question.type === 'long_answer') {
            if (!barData[question.question_text]) {
                barData[question.question_text] = { total: 0, count: 0 };
            }
            barData[question.question_text].total += parseFloat(response.answers);
            barData[question.question_text].count++;
        }
    });

    // Calculate averages for bar graph
    Object.keys(barData).forEach(key => {
        barData[key].average = barData[key].total / barData[key].count;
    });

    return { pieData, barData };
}

// Function to render charts using Chart.js
function renderCharts(pieData, barData) {
    // Render Pie Chart
    const pieChartCanvas = document.getElementById('pieChart');
    new Chart(pieChartCanvas.getContext('2d'), {
        type: 'pie',
        data: {
            labels: Object.keys(pieData),
            datasets: Object.keys(pieData).map(question => ({
                label: question,
                data: Object.values(pieData[question]),
                backgroundColor: getRandomColors(Object.values(pieData[question]).length),
            })),
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (context.parsed.y !== null) {
                                label += ': ' + context.parsed.y;
                            }
                            return label;
                        },
                    },
                },
            },
        },
    });

    // Render Bar Graph
    const barGraphCanvas = document.getElementById('barGraph');
    new Chart(barGraphCanvas.getContext('2d'), {
        type: 'bar',
        data: {
            labels: Object.keys(barData),
            datasets: [{
                label: 'Average Response',
                data: Object.values(barData).map(item => item.average.toFixed(2)),
                backgroundColor: getRandomColors(Object.keys(barData).length),
            }],
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });
}

// Function to generate random colors
function getRandomColors(count) {
    const colors = [];
    for (let i = 0; i < count; i++) {
        colors.push(`rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.2)`);
    }
    return colors;
}

// Main function to fetch data and render charts
async function main() {
    try {
        const responses = await fetchResponses();
        const questions = {!! json_encode($questions) !!}; // This assumes $questions is passed to the Blade view from Laravel

        const { pieData, barData } = processDataForCharts(responses, questions);
        renderCharts(pieData, barData);
    } catch (error) {
        console.error('Error processing or rendering charts:', error);
    }
}

// Run main function on page load
window.addEventListener('DOMContentLoaded', main);
