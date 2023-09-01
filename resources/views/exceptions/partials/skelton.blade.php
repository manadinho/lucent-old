<style>
    .likes-skelton {
        margin: 10px;
        height: 20px;
        width: 150px;

        animation: likes-skelton-loading 1s linear infinite alternate;
    }

    @keyframes likes-skelton-loading {
        0% {
            background-color: hsl(200, 20%, 70%);
        }

        100% {
            background-color: hsl(200, 20%, 95%);
        }
    }

    .delete-skelton {
        height: 40px;
        width: 40px;
        border-radius: 50%;

        animation: delete-skelton-loading 1s linear infinite alternate;
    }

    @keyframes delete-skelton-loading {
        0% {
            background-color: hsl(200, 20%, 70%);
        }

        100% {
            background-color: hsl(200, 20%, 95%);
        }
    }

    .exception-name-skelton {
        height: 20px;
        width: 70%;

        animation: exception-name-skelton-loading 1s linear infinite alternate;
    }

    @keyframes exception-name-skelton-loading {
        0% {
            background-color: hsl(200, 20%, 70%);
        }

        100% {
            background-color: hsl(200, 20%, 95%);
        }
    }

    .exception-message-skelton {
        height: 18px;
        width: 50%;
        margin-top: 10px;

        animation: exception-message-skelton-loading 1s linear infinite alternate;
    }

    @keyframes exception-message-skelton-loading {
        0% {
            background-color: hsl(200, 20%, 70%);
        }

        100% {
            background-color: hsl(200, 20%, 95%);
        }
    }

    .exception-file-skelton {
        height: 15px;
        width: 30%;
        margin-top: 10px;

        animation: exception-file-skelton-loading 1s linear infinite alternate;
    }

    @keyframes exception-file-skelton-loading {
        0% {
            background-color: hsl(200, 20%, 70%);
        }

        100% {
            background-color: hsl(200, 20%, 95%);
        }
    }

    .exception-line-skelton {
        height: 12px;
        width: 5%;
        margin-top: 10px;

        animation: exception-line-skelton-loading 1s linear infinite alternate;
    }

    @keyframes exception-line-skelton-loading {
        0% {
            background-color: hsl(200, 20%, 70%);
        }

        100% {
            background-color: hsl(200, 20%, 95%);
        }
    }

    .chart-skelton {
        height: 150px;
        width: 425px;
        margin-top: 10px;
        margin-bottom: 10px;
        margin-right: 10px;

        animation: chart-skelton-loading 1s linear infinite alternate;
    }

    @keyframes chart-skelton-loading {
        0% {
            background-color: hsl(200, 20%, 70%);
        }

        100% {
            background-color: hsl(200, 20%, 95%);
        }
    }
</style>

<div class=" bg-[#fffffe] rounded mt-4 log-card shadow-xl">
    <div class="flex mt-1">
        <div class="p-5 w-80">
            <div class="likes-skelton bg-[#FF241F] pl-1 pr-1 rounded">
            </div>
            <div class="likes-skelton bg-[#FF241F] pl-1 pr-1 rounded mt-1">
            </div>
            <div class="likes-skelton bg-[#FF241F] pl-1 pr-1 rounded mt-1">
            </div>
            <div class="m-10">
                <div class=" delete-skelton rounded"></div>
            </div>
        </div>
        <div class="vl ml-3"></div>
        <div class="ml-4 p-5 w-full">
            <h2 class="exception-name-skelton text-[#0d0d0d] text-2xl"></h2>
            <h1 class="exception-message-skelton text-[#0d0d0d] text-xl"></h1>
            <p class="exception-file-skelton text-[#121629] text-sm"></p>
            <p class=" exception-line-skelton text-[#121629] text-sm">

            </p>
            <p class=" exception-line-skelton text-text-[#121629] text-sm">

            </p>
        </div>
        <div class="p-4 chart-skelton" style="width: 300px margin-left:auto">

        </div>
    </div>

</div>
