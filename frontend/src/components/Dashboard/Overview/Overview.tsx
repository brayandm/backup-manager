"use client";

import React from "react";
import { LineChart } from "@mui/x-charts/LineChart";
import { BarChart } from "@mui/x-charts/BarChart";
import { format, subDays, subMonths } from "date-fns";
import {
  Card,
  Grid,
  CardContent,
  Typography,
  CircularProgress,
  Alert,
} from "@mui/material";
import { formatBytes } from "@/utils/formatting";
import { PieChart } from "@mui/x-charts";
import useSWR from "swr";
import { get } from "@/lib/backendApi";

interface OverviewProps {}

const fetcher = (url: string) => get(url);

interface OverviewData {
  week_backup_data: number[];
  week_migration_data: number[];
  year_backup_data: number[];
  year_migration_data: number[];
  storage_servers: {
    name: string;
    used_space: number;
    free_space: number;
  }[];
  summary_data: {
    total_storage_servers: number;
    total_backups: number;
    total_backup_configurations: number;
    total_data_sources: number;
    total_migrations: number;
    total_migration_configurations: number;
    total_space_used: number;
  };
}

function Overview({}: OverviewProps) {
  const { data, error, isLoading, mutate } = useSWR(
    "/analytics/get-overview",
    fetcher
  );

  let overviewData = null;

  if (data) {
    overviewData = data.data as OverviewData;
  }

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

  return !isLoading && !error && overviewData ? (
    <div
      style={{
        display: "flex",
        flexDirection: "row",
        justifyContent: "space-between",
        width: "84vw",
        marginTop: "20px",
      }}
    >
      <div
        style={{
          display: "flex",
          flexDirection: "column",
          gap: "50px",
          justifyContent: "center",
          alignItems: "center",
          padding: "20px",
        }}
      >
        <Grid container spacing={2} justifyContent="center">
          {Object.entries(overviewData!.summary_data).map(([key, value]) => (
            <Grid item xs={12} sm={6} md={4} key={key}>
              <Card sx={{ minwidth: 200 }}>
                <CardContent style={{}}>
                  <Typography variant="subtitle1" component="div">
                    {key
                      .replace(/([A-Z])/g, " $1")
                      .trim()
                      .replace(/\b\w/g, (char) => char.toUpperCase())}
                  </Typography>
                  <Typography variant="h6" component="div">
                    {key === "totalSpaceUsed" ? formatBytes(value) : value}
                  </Typography>
                </CardContent>
              </Card>
            </Grid>
          ))}
        </Grid>
        <Grid container spacing={4} justifyContent="center">
          {overviewData!.storage_servers.map((server, index) => (
            <Grid item xs={12} sm={6} md={4} key={index}>
              <div
                style={{
                  display: "flex",
                  flexDirection: "column",
                  gap: "10px",
                  alignItems: "center",
                  justifyContent: "center",
                }}
              >
                <Typography variant="h6" component="div">
                  {server.name}
                </Typography>
                <PieChart
                  series={[
                    {
                      data: [
                        {
                          id: "Used Space",
                          value:
                            (server.used_space /
                              (server.used_space + server.free_space)) *
                            100,
                          label: formatBytes(server.used_space) + " Used",
                        },
                        {
                          id: "Free Space",
                          value:
                            (server.free_space /
                              (server.used_space + server.free_space)) *
                            100,
                          label: formatBytes(server.free_space) + " Free",
                        },
                      ],
                    },
                  ]}
                  width={400}
                  height={100}
                />
              </div>
            </Grid>
          ))}
        </Grid>
      </div>
      <div
        style={{
          display: "flex",
          flexDirection: "column",
          gap: "20px",
        }}
      >
        <div
          style={{
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            justifyContent: "center",
            gap: "20px",
          }}
        >
          <Typography variant="h6" gutterBottom>
            Backups & Migrations in the Last Week
          </Typography>
          <LineChart
            width={500}
            height={300}
            series={[
              { data: overviewData.week_backup_data, label: "Backups" },
              { data: overviewData.week_migration_data, label: "Migrations" },
            ]}
            xAxis={[{ scaleType: "point", data: xLabelsAbbreviatedWeek }]}
          />
        </div>
        <div
          style={{
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            justifyContent: "center",
            gap: "20px",
          }}
        >
          <Typography variant="h6" gutterBottom>
            Backups & Migrations in the Last Year
          </Typography>
          <LineChart
            width={500}
            height={300}
            series={[
              { data: overviewData.year_backup_data, label: "Backups" },
              { data: overviewData.year_migration_data, label: "Migrations" },
            ]}
            xAxis={[{ scaleType: "point", data: xLabelsMonth }]}
          />
        </div>
      </div>
    </div>
  ) : isLoading ? (
    <div
      style={{
        width: "84vw",
        height: "60vh",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
      }}
    >
      <CircularProgress />
    </div>
  ) : (
    <div
      style={{
        position: "fixed",
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        width: "300px",
        top: "10%",
        left: "50%",
        zIndex: 1,
      }}
    >
      <Alert severity="error"> Error fetching data </Alert>
    </div>
  );
}

export default Overview;
