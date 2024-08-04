"use client";

import { Typography } from "@mui/material";
import React from "react";
import { LineChart } from "@mui/x-charts/LineChart";
import { format, subDays } from "date-fns";

interface OverviewProps {}

function Overview({}: OverviewProps) {
  const backupData = [5, 3, 6, 2, 8, 4, 7];

  const today = new Date();
  const xLabels = Array.from({ length: 7 }, (_, i) =>
    format(subDays(today, i), "EEEE")
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

  const xLabelsAbbreviated = xLabels.map((day) => dayAbbreviations[day]);

  return (
    <Typography variant="h2" sx={{ marginTop: "200px", textAlign: "center" }}>
      <LineChart
        width={500}
        height={300}
        series={[{ data: backupData, label: "Backups" }]}
        xAxis={[{ scaleType: "point", data: xLabelsAbbreviated }]}
      />
    </Typography>
  );
}

export default Overview;
