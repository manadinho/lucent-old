<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Errors') }} ({{ $project->name }})
        </h2>
    </x-slot>

    <div class="mb-4 text-sm text-gray-600 max-w-7xl mx-auto sm:px-6 lg:px-4">
        <nav class="flex bg-gray-800 text-white py-3 px-5 rounded-lg mt-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-black-700 hover:text-black-900 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        {{ __('Dashboard')}}
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-black-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="{{ route('teams.index') }}" class="text-black-700 hover:text-black-900 ml-1 md:ml-2 text-sm font-medium">{{ __("Teams")}}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-black-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="{{ route('teams.projects', $project->team_id) }}" class="text-black-700 hover:text-black-900 ml-1 md:ml-2 text-sm font-medium">{{ __("Projects")}}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-black-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-black-400 ml-1 md:ml-2 text-sm font-medium">{{ __('Errors') }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="alpine()" x-init="getExceptions(); getExceptionsCount(); getExceptionsMainChartData();">
                <div class="p-5">
                    <div class=" flex justify-end items-center gap-2">
                        <span class="filter-btn rounded px-2 py-1 filter-section"> {{ __('Total') }} <span x-text="exceptionCount"></span> </span>
                        <span class=" filter-section p-2 rounded flex gap-2">
                            <span @click="doFilter()" :class="filter == '12h' ? 'active-filter-btn' : 'filter-btn'" class="rounded px-2 py-1 filter-section cursor-pointer">12h</span>
                            <span @click="doFilter('day')" :class="filter == 'day' ? 'active-filter-btn' : 'filter-btn'" class="rounded px-2 py-1 filter-section cursor-pointer">Day</span>
                            <span @click="doFilter('week')" :class="filter == 'week' ? 'active-filter-btn' : 'filter-btn'" class="rounded px-2 py-1 filter-section cursor-pointer">Week</span>
                            <span @click="doFilter('all')" :class="filter == 'all' ? 'active-filter-btn' : 'filter-btn'" class="rounded px-2 py-1 filter-section cursor-pointer">All</span>
                            <span @click="doFilter('snoozed')" :class="filter == 'snoozed' ? 'active-filter-btn' : 'filter-btn'" class="rounded px-2 py-1 filter-section cursor-pointer">Snoozed</span>
                            <span @click="doFilter('resolved')" :class="filter == 'resolved' ? 'active-filter-btn' : 'filter-btn'" class="rounded  px-2 py-1 filter-section cursor-pointer">Resolved</span>
                        </span>
                    </div>
                    <div class=" flex justify-center mt-2">
                        <div>
                            <canvas id="main-chart" style="height:40vh; width:65vw"></canvas>
                        </div>
                    </div>
                </div>    

                <template x-if="loading">
                    <section class="m-10">
                        @include('exceptions.partials.skelton')
                        @include('exceptions.partials.skelton')
                        @include('exceptions.partials.skelton')
                        @include('exceptions.partials.skelton')
                        @include('exceptions.partials.skelton')
                    </section>
                </template>
                <template x-if="!loading">
                    <section class="m-10 bg-re" id="exception-area"></section>
                </template>
            

        </div>
    </div>
</x-app-layout>

<script src="{{asset('js/chartjs.min.js')}}"></script>
<script>
    function alpine() {
        return {
            filter: localStorage.getItem('filter') || '12h',
            project_id: '{{ $project->id }}',
            loading: true,
            exceptionCount: 0,
            deleteException(id) {
                const currentElement = document.querySelector(`#delete-btn-${id}`);

                document.querySelector('.confirm-dialog').style.display = 'block';

                document.querySelector('#confirm-dialog-message').textContent = 'Once deleted, you will not be able to recover this log entry';

                const cancelBtn = document.querySelector('#confirm-cancel-btn');

                const deleteBtn = document.querySelector('#confirm-delete-btn');

                deleteBtn.textContent = 'Delete';

                cancelBtn.addEventListener('click', function() {
                    document.querySelector('.confirm-dialog').style.display = 'none';
                    return;
                })

                const self = this;

                deleteBtn.addEventListener('click', function() {
                    deleteBtn.textContent = 'Doing...';
                    fetch(`{{route('projects.exceptions.delete')}}`, {
                        method: 'post',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id,
                            _token: '{{ csrf_token() }}'
                        })
                    }).then(async (_res) => {
                        document.querySelector('.confirm-dialog').style.display = 'none';
                        const res = await _res.json()
                        if (res.success) {
                            removeFadeOut(currentElement.closest('.log-card'), 2000);
                            self.exceptionCount = self.exceptionCount - 1;
                        }
                    }).catch(() => {
                        document.querySelector('.confirm-dialog').style.display = 'none';
                    });
                })
            },
            snoozeException(id, title) {
                const currentElement = document.querySelector(`#snooze-btn-${id}`);

                document.querySelector('.confirm-dialog').style.display = 'block';

                document.querySelector('#confirm-dialog-message').textContent = `You want to ${title} this exception`;

                const cancelBtn = document.querySelector('#confirm-cancel-btn');

                const deleteBtn = document.querySelector('#confirm-delete-btn');

                deleteBtn.textContent = title;

                cancelBtn.addEventListener('click', function() {
                    document.querySelector('.confirm-dialog').style.display = 'none';
                    return;
                })

                const self = this;

                deleteBtn.addEventListener('click', function() {
                    deleteBtn.textContent = 'Doing...';

                    fetch(`{{route('projects.exceptions.snooze')}}`, {
                        method: 'post',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id,
                            _token: '{{ csrf_token() }}'
                        })
                    }).then(async (_res) => {
                        document.querySelector('.confirm-dialog').style.display = 'none';
                        const res = await _res.json()
                        if (res.success) {
                            removeFadeOut(currentElement.closest('.log-card'), 2000);
                            self.exceptionCount = self.exceptionCount - 1;
                        }
                    }).catch(() => {
                        document.querySelector('.confirm-dialog').style.display = 'none';
                    });
                })
            },
            resolveException(id, title) {
                const currentElement = document.querySelector(`#resolve-btn-${id}`);

                document.querySelector('.confirm-dialog').style.display = 'block';

                document.querySelector('#confirm-dialog-message').textContent = `You want to ${title} this exception`;

                const cancelBtn = document.querySelector('#confirm-cancel-btn');

                const deleteBtn = document.querySelector('#confirm-delete-btn');

                deleteBtn.textContent = title;

                cancelBtn.addEventListener('click', function() {
                    document.querySelector('.confirm-dialog').style.display = 'none';
                })

                const self = this;

                deleteBtn.addEventListener('click', function() {
                    deleteBtn.textContent = 'Doing...';

                    fetch(`{{route('projects.exceptions.resolve')}}`, {
                        method: 'post',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id,
                            _token: '{{ csrf_token() }}'
                        })
                    }).then(async (_res) => {
                        document.querySelector('.confirm-dialog').style.display = 'none';
                        const res = await _res.json()
                        if (res.success) {
                            removeFadeOut(currentElement.closest('.log-card'), 2000);
                            self.exceptionCount = self.exceptionCount - 1;
                        }
                    }).catch(() => {
                        document.querySelector('.confirm-dialog').style.display = 'none';
                    });
                })
            },
            doFilter(value = '12h') {
                if (!['12h', 'day', 'week', 'all', 'snoozed', 'resolved'].includes(value)) {
                    alert('Value is not correct');
                    return true;
                }
                
                this.filter = value;
                localStorage.setItem('filter', this.filter)
                this.getExceptionsCount(this.filter)
                this.getExceptions(this.filter);
                this.getExceptionsMainChartData(this.filter);
            },
            getExceptions() {
                /** TO CHECK THE CURRENT PAGE AND CALL API IF CURRENT PAGE IS INDEX */

                this.loading = true;
                fetch(`{{url('/projects/exceptions/fetch')}}/${this.project_id}/${this.filter}`).then(async (_res) => {
                    this.loading = false
                    const res = await _res.text()

                    /** WRITE CUSTOM METHOD TO SET INNER HTML AND TO RUN JAVASCRIPT */
                    setInnerHTML(document.querySelector('#exception-area'), res)
                }).catch(() => {
                    this.loading = false;
                });
            },

            getExceptionsCount() {
                fetch(`{{url('/projects/exceptions/fetch-count')}}/${this.project_id}/${this.filter}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                }).then(async (_res) => {
                    const res = await _res.json();
                    this.exceptionCount = res.data
                }).catch(undefined)
            },
            getExceptionsMainChartData() {
                fetch(`{{url('/projects/exceptions/fetch-chart-data')}}/${this.project_id}/${this.filter}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                }).then(async (_res) => {
                    const res = await _res.json();
                    drawMainChartData(res.data.dates, res.data.total);
                }).catch(undefined)
            },
            drawCode(index = 0, line) {
                let codeSection = ``;

                for (const key of Object.keys(codeSnippets[index])) {
                    if (line == key) {
                        codeSection += `<p class="bg-red-500">
                    <span class="text-white">${key} ${codeSnippets[index][key]}</span>
                        
                    </p>
                    `;
                        continue;
                    }

                    codeSection += `<p style="line-height:0px">
                    <span class="text-[#0d0d0d]">${key}</span>
                    <span class="text-green-400">
                        ${codeSnippets[index][key]}
                    </span>
                    </p>
                `;
                }

                document.querySelector('#codeArea').innerHTML = codeSection
            },
            formatAiResponse(text) {
                return text.replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            },

            generateAiSolution(id) {
                document.querySelector(`#ai-btn-${id}`).textContent = 'Generating...';
                document.querySelector(`#ai-btn-${id}`).disabled = true;
                
                fetch(`{{url('/projects/exceptions/generate-solution')}}/${id}`, {
                    method: 'get',
                }).then(async (_res) => {
                    document.querySelector(`#ai-btn-${id}`).innerHTML = `
                    <span class="tooltiptext" style="display:flex; justify-content:center; align-items:center">AI <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" fill="currentColor" viewBox="0 0 256 256"><path d="M197.58,129.06l-51.61-19-19-51.65a15.92,15.92,0,0,0-29.88,0L78.07,110l-51.65,19a15.92,15.92,0,0,0,0,29.88L78,178l19,51.62a15.92,15.92,0,0,0,29.88,0l19-51.61,51.65-19a15.92,15.92,0,0,0,0-29.88ZM140.39,163a15.87,15.87,0,0,0-9.43,9.43l-19,51.46L93,172.39A15.87,15.87,0,0,0,83.61,163h0L32.15,144l51.46-19A15.87,15.87,0,0,0,93,115.61l19-51.46,19,51.46a15.87,15.87,0,0,0,9.43,9.43l51.46,19ZM144,40a8,8,0,0,1,8-8h16V16a8,8,0,0,1,16,0V32h16a8,8,0,0,1,0,16H184V64a8,8,0,0,1-16,0V48H152A8,8,0,0,1,144,40ZM248,88a8,8,0,0,1-8,8h-8v8a8,8,0,0,1-16,0V96h-8a8,8,0,0,1,0-16h8V72a8,8,0,0,1,16,0v8h8A8,8,0,0,1,248,88Z"></path></svg></span>
                    `;
                document.querySelector(`#ai-btn-${id}`).disabled = false;
                    const res = await _res.json();
                    if(res.success){
                        const aisection = `
                        <div class="bg-green-50 p-2 mt-1 rounded">
                            <span class="flex items-center pr-3 pl-3.5 font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" fill="currentColor" viewBox="0 0 256 256"><path d="M197.58,129.06l-51.61-19-19-51.65a15.92,15.92,0,0,0-29.88,0L78.07,110l-51.65,19a15.92,15.92,0,0,0,0,29.88L78,178l19,51.62a15.92,15.92,0,0,0,29.88,0l19-51.61,51.65-19a15.92,15.92,0,0,0,0-29.88ZM140.39,163a15.87,15.87,0,0,0-9.43,9.43l-19,51.46L93,172.39A15.87,15.87,0,0,0,83.61,163h0L32.15,144l51.46-19A15.87,15.87,0,0,0,93,115.61l19-51.46,19,51.46a15.87,15.87,0,0,0,9.43,9.43l51.46,19ZM144,40a8,8,0,0,1,8-8h16V16a8,8,0,0,1,16,0V32h16a8,8,0,0,1,0,16H184V64a8,8,0,0,1-16,0V48H152A8,8,0,0,1,144,40ZM248,88a8,8,0,0,1-8,8h-8v8a8,8,0,0,1-16,0V96h-8a8,8,0,0,1,0-16h8V72a8,8,0,0,1,16,0v8h8A8,8,0,0,1,248,88Z"></path></svg>
                                <h5>AI Generated Solution</h5>
                            </span>
                            <p class="text-sm mt-2 pl-2" id="exception-ai-text-${id}">
                            </p>
                        </div>`;  
                        document.querySelector(`#ai-solution-section-${id}`).innerHTML = aisection
                        const formatedRes = this.formatAiResponse(res.data);
                        document.getElementById(`exception-ai-text-${id}`).innerHTML = formatedRes;
                    }
                }).catch(err => {
                    document.querySelector(`#ai-btn-${id}`).innerHTML = `
                    <span class="tooltiptext" style="display:flex; justify-content:center; align-items:center">AI <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" fill="currentColor" viewBox="0 0 256 256"><path d="M197.58,129.06l-51.61-19-19-51.65a15.92,15.92,0,0,0-29.88,0L78.07,110l-51.65,19a15.92,15.92,0,0,0,0,29.88L78,178l19,51.62a15.92,15.92,0,0,0,29.88,0l19-51.61,51.65-19a15.92,15.92,0,0,0,0-29.88ZM140.39,163a15.87,15.87,0,0,0-9.43,9.43l-19,51.46L93,172.39A15.87,15.87,0,0,0,83.61,163h0L32.15,144l51.46-19A15.87,15.87,0,0,0,93,115.61l19-51.46,19,51.46a15.87,15.87,0,0,0,9.43,9.43l51.46,19ZM144,40a8,8,0,0,1,8-8h16V16a8,8,0,0,1,16,0V32h16a8,8,0,0,1,0,16H184V64a8,8,0,0,1-16,0V48H152A8,8,0,0,1,144,40ZM248,88a8,8,0,0,1-8,8h-8v8a8,8,0,0,1-16,0V96h-8a8,8,0,0,1,0-16h8V72a8,8,0,0,1,16,0v8h8A8,8,0,0,1,248,88Z"></path></svg></span>
                    `;
                    document.querySelector(`#ai-btn-${id}`).disabled = false;
                });
            }
        }
    }

    function removeFadeOut(el, speed) {
        let seconds = speed / 1000;
        el.style.transition = `opacity ${seconds}s ease`;

        el.style.opacity = 0;
        setTimeout(function() {
            el.parentNode.removeChild(el);
        }, speed);
    }

    function setInnerHTML(elm, html) {
        elm.innerHTML = html;
        Array.from(elm.querySelectorAll("script")).forEach(oldScript => {
            const newScript = document.createElement("script");
            Array.from(oldScript.attributes)
                .forEach(attr => newScript.setAttribute(attr.name, attr.value));
            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });
    }

    function drawMainChartData(labels, _data) {
        const element = document.getElementById('main-chart');
        element.closest('div').style.display = 'none';

        /** CHECK LABELS AND DATA IS EMPTY */
        if (!labels.length) {
            return true
        }

        element.closest('div').style.display = 'block';
        let chart = document.getElementById('main-chart').getContext('2d'),
        gradient = chart.createLinearGradient(0, 0, 0, 450);
        gradientd = chart.createLinearGradient(0, 0, 0, 450);


        gradient.addColorStop(0, 'rgba(169, 169, 169, 0.5)');
        gradient.addColorStop(0.5, 'rgba(169, 169, 169, 0.25)');
        gradient.addColorStop(1, 'rgba(169, 169, 169, 0)');

        let data = {
            labels,
            datasets: [{
                    label: 'Exception',
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
                        color: 'rgba(200, 200, 200, 0.05)',
                        lineWidth: 1
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: 'rgba(200, 200, 200, 0.08)',
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
