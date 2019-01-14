<style>
    .fa-star-form {
        color: lightgrey;
        float: right;
        border-color: #ddd;
        font-size: 30px;
        margin-right: 7px;
    }

    .radioStar {
        position: absolute;
        opacity: 0;
        z-index: 0;
        float: right;
        width: 1px;
        margin: 0;
        white-space: nowrap;
    }

    .label:hover {
        color: blue;
    }

    @foreach ($aspects as $key => $aspect)
    {{ '.radio' . $key . ':checked ~ .label' . $key }} {
        color: gold;
    }
    {{ '.label' . $key . ':hover, .label' . $key . ':hover ~ .label' . $key }} {
        color: blue !important;
    }
    @endforeach
</style>