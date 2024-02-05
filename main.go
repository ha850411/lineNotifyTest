package main

import (
	"lineNotifyTest/conf"
	"lineNotifyTest/routes"

	"github.com/gin-gonic/gin"
)

func main() {
	// 測試
	// test
	// 初始化 gin 物件
	ginServer := gin.Default()
	// 載入 Router
	routes.SetRouter(ginServer)
	// 啟動
	ginServer.Run(conf.Settings.Port)
}
