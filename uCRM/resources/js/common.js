const nl2br = (str) => {
    var res = str.replace(/\r\n/g, "<br>");
    res = res.replace(/(\n|\r)/g, "<br>");
    return res;
}

// 別のファイルで使えるようにexportする
export { nl2br }

// 初期表示は当日とする JSのDateから取得
const getToday = () => {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = ("0"+(today.getMonth()+1)).slice(-2);
    const dd = ("0"+today.getDate()).slice(-2);
    return yyyy+'-'+mm+'-'+dd;
}

// 別のファイルで使えるようにexportする
export { getToday }