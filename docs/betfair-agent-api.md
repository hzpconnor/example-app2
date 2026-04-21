# Betfair Agent API 文档

Base URL: `https://myagent.mjktech.cn`

---

## GET /matches

获取当日全部足球比赛列表。

### 请求

```
GET /matches
```

无需请求参数。

### 响应

**HTTP 200 OK**

```json
{
  "matches": [
    {
      "matchId": "2978062",
      "homeTeam": "贝联后备队",
      "awayTeam": "马梅洛迪日落后备队",
      "league": "南非后备",
      "commenceTime": "2026-04-21T08:00:00.000Z",
      "ahLabel": "让一半/两",
      "totalLine": 3,
      "bookmaker": "澳门/Crown"
    }
  ]
}
```

### 响应字段

| 字段 | 类型 | 说明 |
|------|------|------|
| `matches` | `array` | 比赛列表 |
| `matches[].matchId` | `string` | 比赛唯一 ID |
| `matches[].homeTeam` | `string` | 主队名称 |
| `matches[].awayTeam` | `string` | 客队名称 |
| `matches[].league` | `string` | 联赛名称 |
| `matches[].commenceTime` | `string` | 开赛时间，ISO 8601 格式（UTC） |
| `matches[].ahLabel` | `string` | 亚盘让球说明（见下方说明） |
| `matches[].totalLine` | `number` | 大小球线（如 `2.5`、`3`、`3.75`） |
| `matches[].bookmaker` | `string` | 数据来源庄家，固定为 `"澳门/Crown"` |

### ahLabel 常见值

| 值 | 含义 |
|----|------|
| `平手` | 让球 0 |
| `受让半球` | 客队受让 0.5 球 |
| `让半球` | 主队让 0.5 球 |
| `让半/一` | 主队让 0.5/1 球 |
| `让一半/两` | 主队让 1.5/2 球 |

---

## GET /matches/:matchId/analyze

获取指定比赛的 AI 盘口分析与预测。

### 请求

```
GET /matches/{matchId}/analyze
```

### 路径参数

| 参数 | 类型 | 说明 |
|------|------|------|
| `matchId` | `string` | 比赛 ID，来自 `/matches` 接口的 `matchId` 字段（如 `"2978062"`） |

### 响应

**HTTP 200 OK**

```json
{
  "matchId": "2978062",
  "homeTeam": "贝联后备队",
  "awayTeam": "马梅洛迪日落后备队",
  "league": "南非后备",
  "commenceTime": "2026-04-21T08:00:00.000Z",
  "handicapRecommendation": "客队受让（即：投注马梅洛迪日落后备队 +1.75）",
  "ouRecommendation": "大球（Over 3 球）",
  "confidence": 85,
  "analysis": "近5场全胜且场均进3.6球；主队近4场3负仅1胜，失球9个。客队早段控局，进攻火力强劲（近5场总进球20），主队防线存在漏洞。"
}
```

### 响应字段

| 字段 | 类型 | 说明 |
|------|------|------|
| `matchId` | `string` | 比赛 ID |
| `homeTeam` | `string` | 主队名称 |
| `awayTeam` | `string` | 客队名称 |
| `league` | `string` | 联赛名称 |
| `commenceTime` | `string` | 开赛时间，ISO 8601 格式（UTC） |
| `handicapRecommendation` | `string` | 亚盘推荐及说明 |
| `ouRecommendation` | `string` | 大小球推荐及说明 |
| `confidence` | `number` | 预测置信度，范围 `0–100`（百分比） |
| `analysis` | `string` | 分析依据，包含近期战绩、进攻/防守数据及市场趋势 |

### 响应示例

```json
{"match":{"matchId":"2944655","homeTeam":"深圳新鹏城","awayTeam":"北京国安","league":"中超","commenceTime":"2026-04-21T11:00:00.000Z","ahLabel":"让半球","totalLine":2.5,"bookmaker":"澳门/Crown"},"odds":{"handicap":[{"bookmaker":"澳(初)","line":-0.5,"homeOdds":0.82,"awayOdds":0.88},{"bookmaker":"澳(即)","line":-0.5,"homeOdds":0.82,"awayOdds":0.88},{"bookmaker":"澳门(初)","line":-0.5,"homeOdds":0.82,"awayOdds":0.88},{"bookmaker":"澳门(即)","line":-0.5,"homeOdds":0.87,"awayOdds":0.99},{"bookmaker":"威廉希尔(初)","line":-0.5,"homeOdds":0.8,"awayOdds":1},{"bookmaker":"威廉希尔(即)","line":-0.5,"homeOdds":0.85,"awayOdds":0.95},{"bookmaker":"Bet365(初)","line":-0.5,"homeOdds":0.79,"awayOdds":0.91},{"bookmaker":"Bet365(即)","line":-0.5,"homeOdds":0.87,"awayOdds":0.95},{"bookmaker":"平博(Pinnacle)(初)","line":-0.5,"homeOdds":0.76,"awayOdds":0.97},{"bookmaker":"平博(Pinnacle)(即)","line":-0.5,"homeOdds":0.85,"awayOdds":0.94},{"bookmaker":"Crown(初)","line":-0.5,"homeOdds":0.83,"awayOdds":0.97},{"bookmaker":"Crown(即)","line":-0.5,"homeOdds":0.89,"awayOdds":0.99},{"bookmaker":"Ladbrokes(初)","line":-0.5,"homeOdds":0.83,"awayOdds":0.97},{"bookmaker":"Ladbrokes(即)","line":-0.5,"homeOdds":0.88,"awayOdds":1},{"bookmaker":"1xBet(初)","line":-0.5,"homeOdds":0.82,"awayOdds":0.94},{"bookmaker":"1xBet(即)","line":-0.5,"homeOdds":0.9,"awayOdds":0.98},{"bookmaker":"利记(初)","line":-0.5,"homeOdds":0.78,"awayOdds":1},{"bookmaker":"利记(即)","line":-0.5,"homeOdds":0.87,"awayOdds":0.99},{"bookmaker":"易胜博(初)","line":-0.75,"homeOdds":0.81,"awayOdds":0.92},{"bookmaker":"易胜博(即)","line":-0.5,"homeOdds":0.8,"awayOdds":0.96},{"bookmaker":"18Bet(初)","line":-0.75,"homeOdds":0.85,"awayOdds":0.95},{"bookmaker":"18Bet(即)","line":-0.5,"homeOdds":0.88,"awayOdds":1},{"bookmaker":"威(初)","line":-0.5,"homeOdds":0.75,"awayOdds":1},{"bookmaker":"威(即)","line":-0.5,"homeOdds":0.84,"awayOdds":0.89},{"bookmaker":"Interwet(初)","line":-0.5,"homeOdds":0.7,"awayOdds":1.05},{"bookmaker":"Interwet(即)","line":-0.5,"homeOdds":0.8,"awayOdds":0.9},{"bookmaker":"Unibet(初)","line":-0.25,"homeOdds":0.97,"awayOdds":0.7},{"bookmaker":"Unibet(即)","line":-0.25,"homeOdds":1.04,"awayOdds":0.65}],"h2h":[{"bookmaker":"欧赔(初)","homeOdds":3.05,"drawOdds":3.45,"awayOdds":1.88},{"bookmaker":"欧赔(即)","homeOdds":3.4,"drawOdds":3.55,"awayOdds":1.99}],"totals":[{"bookmaker":"大小球(即)","line":2.5,"overOdds":0.85,"underOdds":0.99},{"bookmaker":"大小球(初)","line":2.75,"overOdds":0.77,"underOdds":0.93}],"corners":[],"htHandicap":[{"bookmaker":"半场盘(初)","line":-0.25,"homeOdds":0.8,"awayOdds":1.08},{"bookmaker":"半场盘(即)","line":-0.25,"homeOdds":0.8,"awayOdds":1.06}],"htH2h":[{"bookmaker":"半场欧赔(初)","homeOdds":3.4,"drawOdds":2.18,"awayOdds":2.51},{"bookmaker":"半场欧赔(即)","homeOdds":3.9,"drawOdds":2.26,"awayOdds":2.49}],"htTotals":[{"bookmaker":"半场大小(初)","line":1.25,"overOdds":1.06,"underOdds":0.8},{"bookmaker":"半场大小(即)","line":1,"overOdds":0.74,"underOdds":1.11}]},"history":{"homeForm":[{"date":"2026-04-17","venue":"客","opponent":"重庆铜梁龙","score":"0-2","result":"负"},{"date":"2026-04-12","venue":"主","opponent":"云南玉昆","score":"3-4","result":"负"},{"date":"2026-04-05","venue":"主","opponent":"武汉三镇","score":"5-2","result":"胜"},{"date":"2026-03-21","venue":"客","opponent":"青岛西海岸","score":"0-1","result":"负"},{"date":"2026-03-14","venue":"主","opponent":"天津津门虎","score":"1-0","result":"胜"}],"awayForm":[{"date":"2026-04-17","venue":"客","opponent":"浙江俱乐部绿城","score":"0-0","result":"平"},{"date":"2026-04-12","venue":"主","opponent":"成都蓉城","score":"1-2","result":"负"},{"date":"2026-04-04","venue":"客","opponent":"辽宁铁人","score":"1-2","result":"负"},{"date":"2026-03-21","venue":"主","opponent":"上海申花","score":"1-1","result":"平"},{"date":"2026-03-14","venue":"客","opponent":"山东泰山","score":"1-2","result":"负"}],"h2h":[]},"prediction":"【让球盘推荐】客队受让（即投注北京国安 -0.5）\n\n【大小球推荐】大球 2.5\n\n【角球推荐】无盘口\n\n【置信度】78%\n\n【核心理由】\n1. 北京国安虽近5场不胜，但对手含金量高且多为强队，状态优于主队深圳新鹏城（近5场3负）；欧赔隐含胜率客队达48.5%，支持让球合理性。\n2. 深圳主场大开大合（近3主场场均进3球失2球），国安客场防守不稳，初盘2.75降至2.5仍维持大球水位，印证进球预期。\n3. 上半场盘口客队受让-0.25且小球线降至1球，反映市场对国安慢热但后程发力的共识，与全场让球逻辑一致。\n\n—— 分析师注：本场建议优先选择“北京国安 -0.5”，次选“大球2.5”，风险控制建议搭配串关或小额单投。","summary":{"handicapPick":"客队受让（即投注北京国安 -0.5）","totalsPick":"大球 2.5","cornersPick":"无盘口","confidence":"78%","keyReasons":"1. 北京国安虽近5场不胜，但对手含金量高且多为强队，状态优于主队深圳新鹏城（近5场3负）；欧赔隐含胜率客队达48.5%，支持让球合理性。\n2. 深圳主场大开大合（近3主场场均进3球失2球），国安客场防守不稳，初盘2.75降至2.5仍维持大球水位，印证进球预期。\n3. 上半场盘口客队受让-0.25且小球线降至1球，反映市场对国安慢热但后程发力的共识，与全场让球逻辑一致。"}}
```

### 错误响应

**HTTP 404 Not Found**

当传入的 `matchId` 不存在时返回。

```json
{}
```

---

## 注意事项

- 数据来源庄家为**澳门/Crown**，所有比赛均为当日赛程。
- `commenceTime` 为 UTC 时间，北京时间需 +8 小时。
- `/matches/:matchId/analyze` 的 `matchId` 必须使用 `/matches` 返回的真实 ID（数字字符串格式，如 `"2978062"`），不支持简单整数。


