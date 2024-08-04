"use client";

import React from "react";
import { LineChart } from "@mui/x-charts/LineChart";
import { format, subDays, subMonths } from "date-fns";

interface OverviewProps {}

function Overview({}: OverviewProps) {
  const weekBackupData = [5, 3, 6, 2, 8, 4, 7];
  const monthBackupData = [10, 6, 12, 4, 16, 8, 14, 20, 18, 22, 24, 26];

  const today = new Date();
  const xLabelsWeek = Array.from({ length: 7 }, (_, i) =>
    format(subDays(today, i), "EEEE")
  ).reverse();

  const xLabelsMonth = Array.from({ length: 12 }, (_, i) =>
    format(subMonths(today, i), "MMM")
  ).reverse();

  const dayAbbreviations: { [key: string]: string } = {
    Monday: "Mon",
    Tuesday: "Tue",
    Wednesday: "Wed",
    Thursday: "Thu",
    Friday: "Fri",
    Saturday: "Sat",
    Sunday: "Sun",
  };

  const xLabelsAbbreviatedWeek = xLabelsWeek.map(
    (day) => dayAbbreviations[day]
  );

  return (
    <div>
      <LineChart
        width={500}
        height={300}
        series={[{ data: weekBackupData, label: "Backups last week" }]}
        xAxis={[{ scaleType: "point", data: xLabelsAbbreviatedWeek }]}
      />
      <LineChart
        width={500}
        height={300}
        series={[{ data: monthBackupData, label: "Backups last 12 months" }]}
        xAxis={[{ scaleType: "point", data: xLabelsMonth }]}
      />
    </div>
  );
}

export default Overview;
