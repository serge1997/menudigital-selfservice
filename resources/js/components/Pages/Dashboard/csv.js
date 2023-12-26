function convertToCSV(objArray) {
    const array = typeof objArray !== 'object' ? JSON.parse(objArray) : objArray;
    let csv = '';
    const headers = Object.keys(array[0]).join(',') + '\n';

    csv += headers;
    array.forEach((item) => {
        let row = '';
        Object.values(item).forEach((value) => {
            if (row !== '') row += ',';
            row += `"${value}"`;
        });
        csv += row + '\n';
    });

    return csv;
}

function exportCSV(products){
    const fields = ['Product code', 'Product name', 'Cost', 'Supplier name', 'Saldo', 'Medida'];
    const header = { fields };

    try{
        const csv = this.convertToCSV(products);

        const blob = new Blob([csv], {type: '\'text/csv;charset=utf-8;\';'});
        const link = document.createElement('a');

        if (link.download !== undefined){
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'products.csv');
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

    }catch (error) {
        console.error(error);
    }
}
