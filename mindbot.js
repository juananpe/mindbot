tree = {
  "title": "principal",
  "id": 1,
  "formatVersion": 2,
  "ideas": {
    "1": {
      "title": "hijo1",
      "id": 2,
      "ideas": {
        "1": {
          "title": "hijo1.1",
          "id": 7
        },
        "2": {
          "title": "hijo1.2",
          "id": 8
        }
      }
    },
    "11": {
      "title": "hijo2",
      "id": 3
    },
    "-10": {
      "title": "hijo3",
      "id": 4,
      "attr": {
        "style": {
          "background": "#ff0000"
        }
      },
      "ideas": {
        "1": {
          "title": "hijo3.1",
          "id": 10,
          "attr": {
            "style": {
              "background": "#ff9900"
            }
          }
        },
        "2": {
          "title": "hijo3.2",
          "id": 11,
          "attr": {
            "style": {
              "background": "#99cc00"
            }
          }
        }
      }
    },
    "-20": {
      "title": "hijo4",
      "id": 5,
      "attr": {
        "position": [
          45,
          14,
          1
        ],
        "style": {}
      }
    }
  }
}



function matchId(id, json){
  if (!(json && "object" === typeof json)) { return; }
  if (json.title === id) { return json; }
  for (var x in json){
    if (Object.hasOwnProperty.call(json, x)) {
      var result = matchId(id, json[x]);
      if (result !== undefined) { return result; }
    }
  }
}


salida = matchId("hijo3.2", tree)


salida.attr.style.background = "#ff0000"
tree.ideas["-10"].ideas["2"].attr.style

JSON.stringify(tree)



