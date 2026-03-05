// 効果音を再生
export default function audio_play(category, eventHandler = null){
    const playAudio = (audioSetting) => {
        const audio_dir = `audio/`;
        let audio_path = '';
        // カテゴリに応じた音声ファイルパスを生成
        if(category === 'proc'){
            audio_path = `${audio_dir}proc.mp3`;
        }else if (category === 'ng'){
            audio_path = `${audio_dir}ng.mp3`;
        }else if(category === 'complete'){
            audio_path = `${audio_dir}complete.mp3`;
        }else{
            console.warn(`無効なカテゴリ: ${category}`);
            return;
        }
        // Audio オブジェクトを生成して再生
        const audio = new Audio(audio_path);
        audio.currentTime = 0;
        audio.play();
        // 完了時のイベントハンドラを登録
        if(eventHandler && category === 'complete'){
            audio.addEventListener('ended', eventHandler);
        }
    }
    playAudio();
}