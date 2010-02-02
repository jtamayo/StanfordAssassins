package com.stanfordassassins.client;

import java.util.Date;

import com.google.gwt.i18n.client.DateTimeFormat;

public class FormatUtil {

	public static Date parseDate(String killDeadlineString) {
		DateTimeFormat format = DateTimeFormat.getFormat("yyyy-MM-dd hh:mm:ss zzz");
		return format.parse(killDeadlineString + " GMT");
	}

}
