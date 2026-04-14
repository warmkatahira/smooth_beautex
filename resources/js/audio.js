// 効果音を再生
export default function audio_play(category, eventHandler = null, nextCategory = null){
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
        }else if(category === 'ship_usa'){
            audio_path = `${audio_dir}ship_usa.mp3`;
        }else{
            console.warn(`無効なカテゴリ: ${category}`);
            return;
        }
        // Audio オブジェクトを生成して再生
        const audio = new Audio(audio_path);
        audio.currentTime = 0;
        audio.play();
        if(nextCategory){
            // 次の音声がある場合：次の音声を再生し、その終了後にeventHandlerを実行
            audio.addEventListener('ended', () => {
                audio_play(nextCategory, eventHandler);
            });
        } else {
            // 次の音声がない場合：この音声の終了後にeventHandlerを実行
            if(eventHandler){
                audio.addEventListener('ended', eventHandler);
            }
        }
    }
    playAudio();
}