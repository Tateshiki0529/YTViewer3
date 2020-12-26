# アップデート周期のリスト  
---
アップデート周期のリストは以下の通り。
> Referrence: libs/class.cache.php -> Cache::updateCache()  
> /\*\*  
>  \* [REFRESH] キャッシュデータの更新 (updateCache)  
>  \*  
>  \* キャッシュの更新を行う。  
>  \*   
>  \* @access public  
>  \* @param int $circle アップデートサークル  
>  \* @return boolean $result アップデート結果  
>  \* @see アップデート周期のリスト (Referrence: [list.updatecircle.md](http://ytv3.ml/docs/list.updatecircle.md)\)  
>  \* @see 定数一覧 (Referrence: [list.const.md](http://ytv3.ml/docs/list.const.md)\)  
>  \*\*/  

| int $circle | Refresh time              |
|:-----------:|:-------------------------:|
|      0      | `00:00`, `12:00`, `18:00` |
|      1      | `01:00`, `13:00`, `19:00` |
|      2      | `02:00`, `14:00`, `20:00` |
|      3      | `03:00`, `15:00`, `21:00` |
|      4      | `04:00`, `16:00`, `22:00` |
|      5      | `05:00`, `17:00`, `23:00` |