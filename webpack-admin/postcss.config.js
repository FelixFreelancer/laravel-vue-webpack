module.exports = ({ file, options, env }) => {
	var config = {
		plugins:{}
	};

	if(env=='production'){

		/*
		* 📣 : AutoPrefixer
		*/
		config.plugins['autoprefixer'] = {};

		/*
		* 📣 : Convert PX to REMs
		*/
		config.plugins['postcss-pxtorem'] = {
			rootValue: 16,
			unitPrecision: 5,
			propList: ['*'],
			selectorBlackList: [],
			replace: true,
			mediaQuery: false,
			minPixelValue: 0
		};
	}

	return config;
}
