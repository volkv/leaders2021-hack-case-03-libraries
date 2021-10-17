import React from 'react';
import WidgetCollection from '../WidgetCollection';
 
const MainScreen = () => {
	return (
		<div>
			<WidgetCollection title="Рекоммендованые" items={[1,2,3,4,5,6,7]} />
			<WidgetCollection title="История" items={[1,2,3,4]} />
			<WidgetCollection title="Бестселлеры" items={[1,2,3]} />
		</div>
	);
};

export default MainScreen;