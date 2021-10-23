import React, {useState, useEffect } from 'react';
import { Provider } from 'react-redux';
import { BrowserRouter as Router, Switch, Route } from 'react-router-dom';
import Content from './components/Content';
import Logo from './components/Logo';
import Header from './components/Header';
import Navigation from './components/Navigation';
import LayoutContainer from './components/LayoutContainer';
import LayoutContent from './components/LayoutContent';
import LayoutNavigation from './components/LayoutNavigation';
import MainScreen from './components/MainScreen';
import SearchScreen from './components/SearchScreen';
import store from './store';

const App = () => {
    return (
        <Provider store={store}>
            <Router>
                <LayoutContainer>
                    <LayoutNavigation>
                        <Logo />
                        <Navigation />
                    </LayoutNavigation>
                    <LayoutContent>
                        <Switch>
                            <Route path='/search/:query'>
                                <Header />
                            </Route>
                            <Route>
                                <Header />
                            </Route>
                        </Switch>
                        <Content>
                            <Switch>
                                <Route path='/about'>
                                    <span>about</span>
                                </Route>
                                <Route path={['/search/:query', '/search']} component={SearchScreen} />
                                <Route path='/'>
                                    <MainScreen />
                                </Route>
                            </Switch>
                        </Content>
                    </LayoutContent>
                </LayoutContainer>
            </Router>
        </Provider>
    );
};

export default App;