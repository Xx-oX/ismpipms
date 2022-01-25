export default {
    genRandomString(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
        }
        return result;
    },


    createDatatableButton(json) {
        json.data.forEach(function (row) {
            // console.log(row, "11111111111111111");
            if (row.button != undefined) {
                let btnHtml = "";
                row.button.forEach(function (btn, btnIdx) {
                    btnHtml += btnHtml == "" ? "" : "&nbsp;";
                    switch (btn) {
                        case "修改":
                            btnHtml +=
                                '<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" name="modify">修改</button>';
                            break;
                        default:
                            btnHtml += btn;
                            console.log(btn + ' 按鈕 case 不存在，請至 gloFuncs.js 增加。');
                            break;
                    }
                });
                row.button = btnHtml;
            }
        });
    }

}
