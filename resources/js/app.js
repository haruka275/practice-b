import './bootstrap'; // 既存のインポート
import $ from 'jquery'; // jQuery をインポート

window.$ = $; // jQuery をグローバルに追加
window.jQuery = $; // jQuery をグローバルに追加

// jQuery が動作することを確認するための簡単なテスト
$(function() {
    console.log('jQuery is ready!');
});
