document.addEventListener('DOMContentLoaded', function () {
    // キャンバスを作成してbodyに追加
    var canvas = document.createElement('canvas');
    canvas.id = 'snow-canvas';
    document.body.appendChild(canvas);

    var ctx = canvas.getContext('2d');
    var width = window.innerWidth;
    var height = window.innerHeight;
    canvas.width = width;
    canvas.height = height;

    // 雪の粒の設定
    var maxSnowflakes = 100;
    var snowflakes = [];

    for (var i = 0; i < maxSnowflakes; i++) {
        snowflakes.push({
            x: Math.random() * width,
            y: Math.random() * height,
            r: Math.random() * 4 + 1, // 半径
            d: Math.random() * maxSnowflakes // 密度
        });
    }

    // 雪を描画する関数
    function draw() {
        ctx.clearRect(0, 0, width, height);
        ctx.fillStyle = "rgba(255, 255, 255, 0.8)";
        ctx.beginPath();

        for (var i = 0; i < maxSnowflakes; i++) {
            var p = snowflakes[i];
            ctx.moveTo(p.x, p.y);
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2, true);
        }
        ctx.fill();
        update();
    }

    // 雪の位置を更新する関数
    var angle = 0;
    function update() {
        angle += 0.01;
        for (var i = 0; i < maxSnowflakes; i++) {
            var p = snowflakes[i];
            // 下に落ちる動き + 左右の揺れ
            p.y += Math.cos(angle + p.d) + 1 + p.r / 2;
            p.x += Math.sin(angle * 2) * 2;

            // 画面外に出たら上に戻す
            if (p.x > width + 5 || p.x < -5 || p.y > height) {
                if (i % 3 > 0) { // 66.67%の確率
                    snowflakes[i] = { x: Math.random() * width, y: -10, r: p.r, d: p.d };
                } else {
                    // もし雪が右から左へ風に吹かれているような演出をする場合
                    if (Math.sin(angle) > 0) {
                        // 左から入ってくる
                        snowflakes[i] = { x: -5, y: Math.random() * height, r: p.r, d: p.d };
                    } else {
                        // 右から入ってくる
                        snowflakes[i] = { x: width + 5, y: Math.random() * height, r: p.r, d: p.d };
                    }
                }
            }
        }
    }

    // アニメーションループ
    setInterval(draw, 33);

    // リサイズ対応
    window.addEventListener('resize', function () {
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width;
        canvas.height = height;
    });
});
