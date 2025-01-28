function imprimerEdtIndi(nomEdt) {
  printJS({
    printable: "edtIndi",
    type: "html",
    documentTitle: nomEdt,
    targetStyles: ["*"],
    style: `
      @page {
        size: A4 landscape;
        margin: 0.5cm;
      }
      body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        font-size: 12px;
      }
      #edtIndi {
        width: 100%;
        height: 100%;
        table-layout: fixed;
        border-collapse: collapse;
      }
      #edtIndi th,
      #edtIndi td {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
        vertical-align: middle;
        word-wrap: break-word;
      }
      #edtIndi th {
        background-color: #f2f2f2;
        font-weight: bold;
      }
      #edtIndi caption {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
        text-align: center;
      }
      body * {
        visibility: hidden;
      }
      #edtIndi,
      #edtIndi * {
        visibility: visible;
      }
      #edtIndi {
        position: absolute;
        top: 0;
        left: 0;
        transform: scale(1.7);
        transform-origin: top left;
      }
        
      .no-print {
        display: none !important; 
      }
    `,
  });
}
container;