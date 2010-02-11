package com.stanfordassassins.client;

import java.util.List;

import com.google.gwt.core.client.GWT;
import com.google.gwt.core.client.JsArray;
import com.google.gwt.event.dom.client.ChangeEvent;
import com.google.gwt.uibinder.client.UiBinder;
import com.google.gwt.uibinder.client.UiField;
import com.google.gwt.uibinder.client.UiHandler;
import com.google.gwt.user.client.ui.Composite;
import com.google.gwt.user.client.ui.FlowPanel;
import com.google.gwt.user.client.ui.ListBox;
import com.google.gwt.user.client.ui.Widget;

public class GameHistory extends Composite {

	private static GameHistoryUiBinder uiBinder = GWT.create(GameHistoryUiBinder.class);

	interface GameHistoryUiBinder extends UiBinder<Widget, GameHistory> {
	}

	@UiField
	ListBox oldGamesListBox;
	@UiField
	FlowPanel gameContent;
	private List<Game> games;
	private StanfordAssassins controller;
	private JsArray<News> news;

	// XXX We should be getting our news from somewhere else!!
	public GameHistory(List<Game> games, StanfordAssassins controller, JsArray<News> news) {
		initWidget(uiBinder.createAndBindUi(this));
		this.games = games;
		this.controller = controller;
		this.news = news;
		
		for (Game game : games) {
			oldGamesListBox.addItem(game.getName());
		}
	}

	@UiHandler("oldGamesListBox")
	void onChange(ChangeEvent e) {
		gameContent.clear();
		for (Game game : games) {
			if (game.getName().equals(oldGamesListBox.getItemText(oldGamesListBox.getSelectedIndex()))){
				MyGame gameWidget = new MyGame(game, controller);
				gameContent.add(gameWidget);
				gameWidget.updateNews(news);
			}
		}
	}

}
