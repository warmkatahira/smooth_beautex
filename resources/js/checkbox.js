export default function get_checkbox(){
    // name属性がchk[]の要素を取得
    const chk = document.getElementsByName("chk[]");
    // カウント用の変数をセット
    let count = 0;
    let all = 0;
    // 取得した要素の分だけループ処理
    for (let i = 0; i < chk.length; i++) {
        // 要素の数をカウントしている
        all++;
        // チェックボックスがONになっている要素をカウント
        if (chk[i].checked) {
            count++;
        }
    }
    return [chk, count, all];
}

// チェックアイコン(thタグ)を押下したら
$('#all_check').on("click",function(){
    // チェックボックス要素関連の情報を取得
    const [chk, count, all] = get_checkbox();
    // チェックボックスがONの要素数と取得した全ての要素数が同じかどうかでONにするかOFFにするか判定
    if (count == all) {
        for(let i = 0; i < chk.length; i++) {
            // OFF
            chk[i].checked = false
        }
    } else {
        for(let i = 0; i < chk.length; i++) {
            // ON
            chk[i].checked = true
        }
    }
});