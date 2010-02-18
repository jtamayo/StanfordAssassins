package com.stanfordassassins.client;

import com.google.gwt.event.dom.client.BlurEvent;
import com.google.gwt.event.dom.client.BlurHandler;
import com.google.gwt.event.dom.client.FocusEvent;
import com.google.gwt.event.dom.client.FocusHandler;
import com.google.gwt.user.client.ui.TextBox;

public class Watermark implements BlurHandler, FocusHandler{

	private String watermark;
	
	public Watermark(String watermark) {
		this.watermark = watermark;
	}

	public static Watermark addWatermark(final TextBox box, String watermark) {
		final Watermark w = new Watermark(watermark);
		
		box.addBlurHandler(w);
		
		box.addFocusHandler(w);
		
		w.enableWatermark(box);
		return w;
	}

	private void enableWatermark(TextBox box) {
		String text = box.getText();
		if (text.length() == 0 || text.equalsIgnoreCase(watermark)) {
			box.setText(watermark);
			box.addStyleDependentName("watermark");
		}
	}

	public void onBlur(BlurEvent event) {
		enableWatermark((TextBox) event.getSource());
	}

	public void onFocus(FocusEvent event) {
		TextBox box = (TextBox) event.getSource();
		box.removeStyleDependentName("watermark");
		if (watermark.equals(box.getText())) {
			box.setText("");
		}
		
	}
}
