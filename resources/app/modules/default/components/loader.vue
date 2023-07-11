<template>
    <div class="loader-container">
        <span class="material-symbols-outlined loader">
            progress_activity
        </span>
    </div>
</template>

<style lang="scss">
    .loader-container {
        position: relative;
        height: 40px;
        width: 100%;
        overflow: hidden;
        padding: 4px;
        text-align: center;

        .loader {
            font-size: 32px;
            font-weight: 900;
            color: #333;
            animation: loader_spin 1s linear infinite;
            transform-origin: 50% 50%;

        }
    }



    @keyframes loader_spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

</style>
