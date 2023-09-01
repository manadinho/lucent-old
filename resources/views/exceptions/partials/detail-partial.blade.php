<script type="text/javascript" defer="">
    const traces = @json($log->traces);
    const codeSnippets = @json($log->codeSnippets);
    const firstTraceLine = '{{$log->traces[0]->line}}';

    drawCode(null, 0, firstTraceLine);

    function drawCode(el, index = 0, line) {
        document.querySelector('#file-name').textContent = getFileName(traces[index]['file']);

        let codeSection = ``;

        if (el) {
            makeTraceActive(el);
        }

        for (const key of Object.keys(codeSnippets[index])) {
            if (line == key) {
                codeSection += `<p style="line-height:0px; margin-top:-10px; font-weight: bolder; color: #f91807">
                <span class="">${key}</span>
                <span class="">
                    ${codeSnippets[index][key]}
                </span>
                </p>
            `;
                continue;
            }

            codeSection += `<p style="line-height:0px; margin-top:-10px">
                <span class="">${key}</span>
                <span class="">
                    ${codeSnippets[index][key]}
                </span>
                </p>
            `;
        }

        document.querySelector('#codeArea').innerHTML = codeSection
    }


    function makeTraceActive(el) {
        const activeElement = document.querySelector('.active-trace');
        if (activeElement) {
            activeElement.classList.remove('active-trace')
        }

        el.classList.add('active-trace');
    }

    function getFileName(path) {
        const segments = path.split('/').filter(segment => segment !== ''); // Split the string into segments

        if (segments.length >= 2) {
            return segments.slice(-2).join('/');
        } 
        
        return path;
    }



    // function getAlpineData() {
    //     return {
    //         drawCode(index = 0, line) {
    //             let codeSection = ``;

    //             for (const key of Object.keys(codeSnippets[index])) {
    //                 if (line == key) {
    //                     codeSection += `<p class="bg-red-500">
    //                     <span class="text-white">${key} ${codeSnippets[index][key]}</span>
                            
    //                     </p>
    //                     `;
    //                     continue;
    //                 }

    //                 codeSection += `<p style="line-height:0px">
    //                     <span class="text-[#0d0d0d]">${key}</span>
    //                     <span class="text-green-400">
    //                         ${codeSnippets[index][key]}
    //                     </span>
    //                     </p>
    //                 `;
    //             }

    //             document.querySelector('#codeArea').innerHTML = codeSection
    //         }

    //     }
    // }
</script>
