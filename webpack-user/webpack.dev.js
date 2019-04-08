const merge = require("webpack-merge");
const common = require("./webpack.common.js");
var webpack = require("webpack");

//var host = "192.168.1.199";
var host = "localhost";
//var host = "172.20.10.6";
var port = "8080";

module.exports = merge(common, {
	output: {
		publicPath: `http://${host}:${port}/`
	},
	devServer: {
		contentBase: `./src`,
		hot: true,
		inline: true,
		headers: { "Access-Control-Allow-Origin": "*" },
		port: 8080,
		open: true
		//watchContentBase: true
	},
	plugins: [
		new webpack.DefinePlugin({
			"process.env.NODE_ENV": JSON.stringify("development"),
			ENV: JSON.stringify("development"),
			IS_DEV: JSON.stringify(true),
			HOST: `http://${host}:${port}`
		}),
		new webpack.HotModuleReplacementPlugin() //This is required as we've added Dev Server config in webpack.config
	]
});
