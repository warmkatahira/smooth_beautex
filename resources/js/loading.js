export default function start_loading(){
    const loading = document.getElementById('loading');
    loading.classList.remove('hidden');
}

// ロードが終わってから0.5秒は表示し続ける
window.addEventListener('load', () => {
    setTimeout(() => {
        document.getElementById('loading').classList.add('hidden');
    }, 500);
});