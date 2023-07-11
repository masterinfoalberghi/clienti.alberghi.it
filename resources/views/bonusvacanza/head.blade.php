.hotel-item .button,
.hotel-item .name {
    padding: 10px 0;
    border-bottom: 1px solid #ddd;
}

body,
input,
label {
    font-weight: 400;
}

@media (max-width: 768px) {
    body,
    html {
        width: 100%;
        overflow-x: hidden;
        min-width: 1px !important;
    }
    main {
        padding: 20px;
        margin:0;
    }
    body,
    input,
    label {
        font-size: 17px;
        font-weight: 400;
    }
    input[type="text"] {
        font-size: 17px;
    }
    h2 {
        line-height: 30px;
    }
    ol,
    ol li {
        margin: 0;
        padding: 0;
    }
    ol {
        padding: 15px;
    }
    td.name span {
        display: none;
    }
    section h2 {
        display: block;
    }
    main {
        padding: 20px;
    }
    section.help a.link {
        color: #1A0DAB;
        text-decoration: underline;
    }
    section.help a.link:hover {
        text-decoration: underline;
    }
    section.help a.link:visited {
        color: #609;
    }
    .evidence {
        padding: 15px 15px 15px 40px;
    }
    .evidence input[type=radio] {
        top: 18px;
        left: 9px;
    }
    .buttons {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        border-top: 1px solid #ddd;
        padding: 15px;
        text-align: center;
    }
    input.error {
        border-color: #e53935 !important;
        background: #ffcdd2;
    }
    #period-items .items {
        padding: 15px;
    }
}