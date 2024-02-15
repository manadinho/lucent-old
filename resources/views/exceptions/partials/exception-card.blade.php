@forelse($exceptions as $exception)
<a href="{{ route('projects.exceptions.get-one', [$project->id, $exception->id]) }}">
    <div class="mt-5 bg-gray-50 shadow sm:rounded-lg border log-card">
        <div class="flex mt-1">
            <div class="p-2 w-80">
                <x-bladewind.tag label="last: {{ $exception->occurrence_times[count($exception->occurrence_times) - 1]->diffForHumans() }}" color="gray" />
                <x-bladewind.tag label="first: {{ $exception->occurrence_times[0]->diffForHumans() }}" color="gray" />
                <x-bladewind.tag label="occurrences: {{ $exception->occurrence_count }} {{ $exception->occurrence_count > 1 ? 'Times' : 'Time' }}" color="gray" />
                @if($exception->reopen_count > 0)
                    <x-bladewind.tag label="Reopen: {{ $exception->reopen_count }}" color="red" />
                @endif
                <div class="mt-2 flex gap-2">
                    

                </div>
            </div>
            <span aria-hidden="true" class="inline-block w-1 bg-[#cbd5e1]"></span>
            <div class="ml-4 p-2 w-full" style="overflow: hidden; position:relative">
                <h2 class=" tracking-wider text-2xl text-gray-900/90 mb-1 label"><b>{{ $exception->name }}</b></h2>
                <h1 class="tracking-wider text-sm text-gray-900/90 mb-1 label">{{ $exception->message }}</h1>
                <div class="mt-5" style="position: absolute; bottom: 5px">
                    <x-bladewind.button type="secondary" size="tiny" id="delete-btn-{{ $exception->id }}" @click.prevent="deleteException('{{ $exception->id }}')" >
                        <span class="tooltiptext">Delete</span>
                        <x-bladewind.icon name="trash" />
                    </x-bladewind.button>
                    <x-bladewind.button type="secondary" size="tiny" id="snooze-btn-{{ $exception->id }}" @click.prevent="snoozeException('{{ $exception->id }}', '{{ $exception->is_snoozed ? 'Unsnooze' : 'Snooze' }}')" class="ml-3 mt-1" >
                        <span class="tooltiptext">{{ $exception->is_snoozed ? 'Unsnooze' : 'Snooze'}}</span>
                        <x-bladewind.icon name="bell-snooze" />
                    </x-bladewind.button>
                    <x-bladewind.button type="secondary" size="tiny" id="resolve-btn-{{ $exception->id }}" @click.prevent="resolveException('{{ $exception->id }}', '{{ $exception->is_resolved ? 'Unresolved' : 'Resolved' }}')" class="ml-3 mt-1" >
                        <span class="tooltiptext">{{ $exception->is_resolved ? 'Unresolved' : 'Resolved'}}</span>
                        <x-bladewind.icon name="check-circle" />
                    </x-bladewind.button>
                    @if($hasAiKey)
                        <x-bladewind.button type="primary" size="tiny" color="green" id="ai-btn-{{ $exception->id }}" @click.prevent="generateAiSolution('{{ $exception->id }}')" >
                            <span class="tooltiptext" style="display:flex; justify-content:center; align-items:center">AI <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" fill="currentColor" viewBox="0 0 256 256"><path d="M197.58,129.06l-51.61-19-19-51.65a15.92,15.92,0,0,0-29.88,0L78.07,110l-51.65,19a15.92,15.92,0,0,0,0,29.88L78,178l19,51.62a15.92,15.92,0,0,0,29.88,0l19-51.61,51.65-19a15.92,15.92,0,0,0,0-29.88ZM140.39,163a15.87,15.87,0,0,0-9.43,9.43l-19,51.46L93,172.39A15.87,15.87,0,0,0,83.61,163h0L32.15,144l51.46-19A15.87,15.87,0,0,0,93,115.61l19-51.46,19,51.46a15.87,15.87,0,0,0,9.43,9.43l51.46,19ZM144,40a8,8,0,0,1,8-8h16V16a8,8,0,0,1,16,0V32h16a8,8,0,0,1,0,16H184V64a8,8,0,0,1-16,0V48H152A8,8,0,0,1,144,40ZM248,88a8,8,0,0,1-8,8h-8v8a8,8,0,0,1-16,0V96h-8a8,8,0,0,1,0-16h8V72a8,8,0,0,1,16,0v8h8A8,8,0,0,1,248,88Z"></path></svg></span>
                        </x-bladewind.button>
                    @endif
                </div>
            </div>
            <div class="p-1" style=" margin-left:auto">
                <canvas id="chart-{{$exception->id}}" style="background: #e4e7eb; width: 300px; border-radius: 5px; padding: 5px; margin-right: 5px; margin-bottom: 4px;"></canvas>
            </div>
        </div>
        <div id="ai-solution-section-{{ $exception->id }}">
            @if($exception->ai_solution)
                <div class="bg-green-50 p-2 mt-1 rounded">
                    <span class="flex items-center pr-3 pl-3.5 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" fill="currentColor" viewBox="0 0 256 256"><path d="M197.58,129.06l-51.61-19-19-51.65a15.92,15.92,0,0,0-29.88,0L78.07,110l-51.65,19a15.92,15.92,0,0,0,0,29.88L78,178l19,51.62a15.92,15.92,0,0,0,29.88,0l19-51.61,51.65-19a15.92,15.92,0,0,0,0-29.88ZM140.39,163a15.87,15.87,0,0,0-9.43,9.43l-19,51.46L93,172.39A15.87,15.87,0,0,0,83.61,163h0L32.15,144l51.46-19A15.87,15.87,0,0,0,93,115.61l19-51.46,19,51.46a15.87,15.87,0,0,0,9.43,9.43l51.46,19ZM144,40a8,8,0,0,1,8-8h16V16a8,8,0,0,1,16,0V32h16a8,8,0,0,1,0,16H184V64a8,8,0,0,1-16,0V48H152A8,8,0,0,1,144,40ZM248,88a8,8,0,0,1-8,8h-8v8a8,8,0,0,1-16,0V96h-8a8,8,0,0,1,0-16h8V72a8,8,0,0,1,16,0v8h8A8,8,0,0,1,248,88Z"></path></svg>
                        <h5>AI Generated Solution</h5>
                    </span>
                    <p class="text-sm mt-2 pl-2">
                        {!! formatAiResponse($exception->ai_solution) !!}
                    </p>
                </div>
            @endif
        </div>    

    </div>
</a>
<script>
    drawChart(`chart-{{$exception->id}}`, @json($exception->chartLabels), @json($exception->chartData));
    // const labels = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    // const data = [3616.49, 2853.34, 2554.41, 1510.16, 2024.81, 1706.82, 2057.85, 0, 0, 0, 0, 0];
    function drawChart(id, labels, _data) {
        let chart = document.getElementById(id).getContext('2d'),
        gradient = chart.createLinearGradient(0, 0, 0, 450);
        gradientd = chart.createLinearGradient(0, 0, 0, 450);


        gradient.addColorStop(0, 'rgba(169, 169, 169, 0.5)');
        gradient.addColorStop(0.5, 'rgba(169, 169, 169, 0.25)');
        gradient.addColorStop(1, 'rgba(169, 169, 169, 0)');

        let data = {
            labels,
            datasets: [{
                    label: 'OCCURRENCES',
                    backgroundColor: gradient,
                    pointBackgroundColor: '#cbd5e1',
                    borderWidth: 1,
                    borderColor: '#cbd5e1',
                    data: _data
                }

            ]
        };




        let options = {
            responsive: true,
            maintainAspectRatio: true,
            animation: {
                easing: 'easeInOutQuad',
                duration: 520
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: '#cbd5e1',
                        lineWidth: 1
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: '#cbd5e1',
                        lineWidth: 1
                    }
                }]
            },
            elements: {
                line: {
                    tension: 0.4
                }
            },
            legend: {
                display: false
            },
            point: {
                backgroundColor: 'white'
            },
            tooltips: {
                titleFontFamily: 'Open Sans',
                backgroundColor: 'rgba(0,0,0,0.3)',
                titleFontColor: 'white',
                caretSize: 5,
                cornerRadius: 2,
                xPadding: 10,
                yPadding: 10
            }
        };


        let chartInstance = new Chart(chart, {
            type: 'line',
            data: data,
            options: options
        });
    }
</script>
@empty
<div style="height: 350px;">
    <div class=" mt-4 flex justify-center">
        <img src="{{ asset('images/no-data-found.svg') }}" width="400px">
    </div>

    <div class=" flex justify-center">
        <p class="no-data-found-text">No exception found!</p>
    </div>
</div>
@endforelse
