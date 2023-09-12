package routes

import (
	"encoding/json"
	"fmt"
	"io/ioutil"
	"lineNotifyTest/conf"
	"net/http"
	"net/url"
	"strings"

	"github.com/gin-contrib/multitemplate"
	"github.com/gin-gonic/gin"
)

/*
* 共用模板設定
* @return multitemplate.Renderer
 */
func createMyRender() multitemplate.Renderer {
	render := multitemplate.NewRenderer()
	render.AddFromFiles("step1", "views/step1.html")
	render.AddFromFiles("step2", "views/step2.html")
	render.AddFromFiles("step3", "views/step3.html")
	return render
}

func SetRouter(r *gin.Engine) {
	// 載入共用模板設定
	r.HTMLRender = createMyRender()
	// set route
	r.GET("/step1", Step1)
	r.GET("/step2", Step2)
	r.GET("/step3", Step3)
	r.POST("/step3", Step3)
	r.POST("/send", Send)
}

func Step1(c *gin.Context) {
	output := make(map[string]interface{})
	output["redirectURL"] = fmt.Sprintf("http://localhost%s/step2", conf.Settings.Port)
	output["clientId"] = conf.Settings.LineNotifyClientId

	c.HTML(http.StatusOK, "step1", output)
}

func Step2(c *gin.Context) {

	output := make(map[string]interface{})
	code, _ := c.GetQuery("code")
	output["code"] = code
	output["redirectURL"] = fmt.Sprintf("http://localhost%s/step2", conf.Settings.Port)
	output["clientId"] = conf.Settings.LineNotifyClientId
	output["clientSecret"] = conf.Settings.LineNotifyClientSecret
	c.HTML(http.StatusOK, "step2", output)
}

func Step3(c *gin.Context) {
	// 呼叫 linebot api 取得 access_token
	form := url.Values{}
	form.Add("grant_type", "authorization_code")
	form.Add("code", c.PostForm("code"))
	form.Add("redirect_uri", c.PostForm("redirect_uri"))
	form.Add("client_id", c.PostForm("client_id"))
	form.Add("client_secret", c.PostForm("client_secret"))

	req, _ := http.NewRequest("POST", "https://notify-bot.line.me/oauth/token", strings.NewReader(form.Encode()))
	req.Header.Set("Content-Type", "application/x-www-form-urlencoded")
	client := &http.Client{}
	resp, _ := client.Do(req)
	response, _ := ioutil.ReadAll(resp.Body)
	var jsonResponse map[string]interface{}
	json.Unmarshal(response, &jsonResponse)

	output := make(map[string]interface{})
	output["jsonStr"] = string(response)

	output["accessToken"] = ""
	if jsonResponse["status"] == float64(200) {
		output["accessToken"] = jsonResponse["access_token"]
	}

	c.HTML(http.StatusOK, "step3", output)
}

func Send(c *gin.Context) {
	form := url.Values{}
	form.Add("message", c.PostForm("message"))
	bearerToken := fmt.Sprintf("Bearer %s", c.PostForm("accessToken"))
	req, _ := http.NewRequest("POST", "https://notify-api.line.me/api/notify", strings.NewReader(form.Encode()))
	req.Header.Set("Content-Type", "application/x-www-form-urlencoded")
	req.Header.Set("Authorization", bearerToken)

	client := &http.Client{}
	resp, _ := client.Do(req)
	response, _ := ioutil.ReadAll(resp.Body)
	c.String(200, string(response))
}
