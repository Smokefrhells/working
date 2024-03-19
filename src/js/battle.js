function attack(url) {
    document.getElementById('attack').innerHTML = '<a><div style="background-image:url(/images/attack_off.png);background-repeat:no-repeat;background-size:cover;height:50px;width:50px;margin: 0 auto;""></div></a>';
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.send();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status != 200) {
                window.location.reload();
            } else {
                stats = JSON.parse(xhr.responseText);
                if (stats.error != 1 && stats.wins != 2) {
                    att_user = stats.att_user;
                    att_vrag = stats.att_vrag;
                    if (hp_vrag > 0 && hp_user > 0) {
                        hp_user = hp_user - att_vrag;
                        hp_vrag = hp_vrag - att_user;
                        if (hp_user < 1) {
                            hp_user = 0;
                            window.location = "?";
                        }
                        if (hp_vrag < 1) {
                            hp_vrag = 0;
                            window.location = "?";
                        }
                        proc_user = (hp_user / hp_user_max) * 100;
                        proc_vrag = (hp_vrag / hp_vrag_max) * 100;
                        proc_del_user = (att_vrag / hp_user_max) * 100;
                        proc_del_vrag = (att_user / hp_vrag_max) * 100;
                        document.getElementById('hp_proc_block_v').style.width = proc_vrag + '%';
                        document.getElementById('hp_proc_block_u').style.width = proc_user + '%';
                        document.getElementById('hp_proc_del_block_v').style.width = proc_del_vrag + '%';
                        document.getElementById('hp_proc_del_block_u').style.width = proc_del_user + '%';
                        document.getElementById('hp_small_v').innerHTML = hp_vrag;
                        document.getElementById('hp_small_u').innerHTML = hp_user;
                        document.getElementById('hp_del_v').innerHTML = '-' + att_user;
                        document.getElementById('hp_del_u').innerHTML = '-' + att_vrag;
                        var audio = new Audio(); // Создаём новый элемент Audio
                        audio.src = '/js/music/attack.mp3?v=1'; // Указываем путь к звуку "клика"
                        audio.autoplay = true; // Автоматически запускаем
                        setTimeout("hp_del()", 500);
                    } else {
                        window.location = "?";
                    }
                }
            }
        }
    }
}

function hp_del(user, vrag) {
    document.getElementById('attack').innerHTML = '<a onclick="attack(\'' + url + '\');return false;" href="?attack"><div style="background-image:url(/images/attack_on.png);background-repeat:no-repeat;background-size:cover;height:50px;width:50px;margin: 0 auto;""></div></a>';
}

